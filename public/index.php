<?php
echo "OCI8 Extension: ";
echo extension_loaded('oci8') ? "Loaded\n" : "Not loaded\n";

echo "OCI8 Client Version: ";
echo oci_client_version() . "\n";

echo "Testing connection...\n";
try {
    $conn = oci_connect('username', 'password', 'hostname/service_name');
    if ($conn) {
        echo "Connection successful!\n";
        oci_close($conn);
    } else {
        echo "Connection failed.\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
