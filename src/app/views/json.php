<?php
/**
 * Default report view
 */
header('Content-type: application/json');
echo json_encode($response, JSON_NUMERIC_CHECK);
