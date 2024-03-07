<?php
$python_code = '
import sys
print("Python version:\n%s"%(sys.version))
';

$command = 'python3 -c ' . escapeshellarg($python_code);

exec($command, $output, $return_var);


echo implode("\n", $output) . "\n";

// Check the return status
if ($return_var === 0) {
    echo "파이썬 돌아감\n";
} else {
    echo "파이썬 안돌아감\n";
}

?>