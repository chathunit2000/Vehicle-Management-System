<?php
require_once '../database/db.php';

class userModel
{
    // Get user info with division name by username and password
    public function getUser($username, $password)
    {
        $rows = array();

        // Create DB connection
        $dbObject = new db();
        $mysqli = $dbObject->getConnection();

        // Prepare the query with a LEFT JOIN to get division name
        $stmt = $mysqli->prepare("
            SELECT u.*, d.division_name 
            FROM news_user u 
            LEFT JOIN division d ON u.division_id = d.division_id 
            WHERE u.UserName = ? AND u.Password = md5(?)");

        if (!$stmt) {
            die('Prepare failed: (' . $mysqli->errno . ') ' . $mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param('ss', $username, $password);

        // Execute the query
        $stmt->execute();

        // Get result set
        $result = $stmt->get_result();

        // Fetch all rows into array
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        // Close the statement
        $stmt->close();

        return $rows;
    }
}
?>
