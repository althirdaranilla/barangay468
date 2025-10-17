<?php
function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
, $_SERVER["HTTP_USER_AGENT"]);
}
function log_activity($user_role, $type, $object, $conn){
    $stmt = $conn->prepare("INSERT INTO logs (number) VALUES (NULL) ");
    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        $hex_id = sprintf('%04X', $last_id);
        $id = "ADM-" . $hex_id;

        $datetime_now = new DateTime('now');
        $datetime_now->setTimezone(new DateTimeZone("Etc/GMT-8"));
        $datetime = $datetime_now->format('Y-m-d H:i:s');

        $action = $type . " " . $object;
        /*
        switch($type){
            case "Added":
                break;
            case "Deleted":
                break;
            case "Updated":
                break;
            case "Processed":
                break;
            case "Generated":
                break;
            case "Viewed":
                break;
            default:
                $action = "Performed an Activity";
        }
        */

        $device = isMobileDevice() ? "Mobile" : "Desktop/Laptop";

        $stmt = $conn->prepare("UPDATE logs SET id=?, user_role=?, action=?, type=?, timestamp=?, device=? WHERE number=?");
        $stmt->bind_param("sssssss", $id, $user_role, $action, $type, $datetime, $device, $last_id);
        if (!$stmt->execute()) {
            echo "<script>console.log('Failed to log activity.');</script>";
        }
        $stmt->close();
    } else {
        $stmt->close();
        echo "<script>console.log('Failed to create announcement.');</script>";
    }
}