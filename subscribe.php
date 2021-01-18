<?php
require_once 'config.php';
require_once 'lib/Database.php';
require_once 'twilio-client.php';
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

$body = $_REQUEST['Body'];
$contact = $_REQUEST['From'];

if (trim(strtoupper($body)) == strtoupper($subscribe_keyword)) {
    $db = new Database();

    $db->query("SELECT `id` FROM `subscribers` WHERE `contact` = :contact");
    $db->bind(":contact", $contact);
    $found = $db->single();

    if (!$found) {
        $db->query("INSERT INTO `subscribers` (`contact`) VALUES (:contact)");
        $db->bind(":contact", $contact);
        $db->execute();
        $message = "You have been subscribed, thank you.";
    } else {
        $message = "You were already subscribed, thank you.";
    }

    $db->close();

} else if (trim(strtoupper($body)) == strtoupper($unsubscribe_keyword)) {
    $db = new Database();
    $db->query("DELETE FROM `subscribers` WHERE `contact` = :contact");
    $db->bind(":contact", $contact);
    $db->execute();
    $message = "You have been unsubscribed, sorry to see you go.";

    $db->close();
} else if (strpos(trim(strtoupper($body)), "BROADCAST") === 0) {
    $db = new Database();
    $db->query("SELECT `id` FROM `subscribers` WHERE `contact` = :contact AND is_admin = 1");
    $db->bind(":contact", $contact);
    $found = $db->single();

    if ($found && count($found) == 1) {
        $db->query("SELECT `contact` FROM `subscribers` WHERE `contact` != :contact");
        $db->bind(":contact", $contact);
        $contacts = $db->resultset();

        $message = preg_replace("/BROADCAST/i", " ", $body);
        $moreMedia = true;
        $mediaIndex = 0;
        $mediaUrls = [];

        while ($moreMedia) {
            if (isset($_POST['MediaUrl' . $mediaIndex])) {
                array_push($mediaUrls, $_POST['MediaUrl' . $mediaIndex]);
                $mediaIndex++;
            } else {
                $moreMedia = false;
            }
        }

        foreach ($contacts as $contact_number) {
            $payload = array("from" => $_REQUEST['To'], "body" => $message);

            if (count($mediaUrls) > 0) {
                foreach ($mediaUrls as $mediaUrl) {
                    $payload["mediaUrl"] = $mediaUrl;
                }
            }

            $GLOBALS['twilioClient']->messages->create($contact_number, $payload);
        }
    } else {
        $message = "You are not allowed to broadcast to this list.";
    }
    $db->close();
} else {
    $message = "You must send a message with the word \"$subscribe_keyword\" in it to get subscribed.";
}
?>
<Response>
    <Message><?php echo $message; ?></Message>
</Response>
