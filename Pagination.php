<?php
include "./db/db_conn.php";

// Query used for the Pagination.
$selectQuery = "SELECT * FROM Orders LIMIT 0, 4";