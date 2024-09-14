<?php
$pepper = base64_encode(random_bytes(32));  // 32 bytes = 256 bits
echo $pepper;
