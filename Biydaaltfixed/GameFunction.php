<?php


$funcName = isset($_POST['funcName']) ? $_POST['funcName'] : null;

if ($funcName === 'sendScore') {
    $username = isset($_POST['Username']) ? $_POST['Username'] : '';
    $score = isset($_POST['score']) ? $_POST['score'] : '';

    $functions = new gameFunctions();
    // select function to execute
    list ($returnCode, $returnData) = $functions->sendScore($username, $score);

    $resultado = array("returnCode" => $returnCode, "returnData" => $returnData);

    echo json_encode($resultado);
    if ($username == null && $score == null){
        echo json_encode(['error' => 'Username or score is missing']);
    }
} else {
    // Handle other funcName values or unknown requests
    echo json_encode(['error' => 'Invalid request']);
}

class gameFunctions
{
    private $db;
    private $connection;

    // constructor
    function __construct()
    {
        require_once 'Connect.php';
        // connecting to database
        $this->db = new DB_Connector();
        $this->connection = $this->db->connect();
    }

    // destructor
    function __destruct()
    {
        $this->db->close();
    }

    public function sendScore($userName, $score)
    {
        if ($userId)
    {
        //the user has an ID assigned
        $result = mysqli_query($this->connection, "SELECT Leaderboard.Highscore FROM Leaderboard WHERE Leaderboard.userId='$userId'");
        if ($result)
        {
            //we have a leaderboard row for the current user
            $rowDataUser = mysqli_fetch_row($result);
            $oldscore = $rowDataUser[0];
            if ($oldscore < $score)
            {
                $result2 = mysqli_query($this->connection, "UPDATE Leaderboard SET Leaderboard.Highscore='$score',Leaderboard.userName='$userName' WHERE Leaderboard.userId='$userId'");
                if ($result2)
                {
                    return array ('0', $userId); // all OK
                }
                else
                {
                    return array ('1', "Error Code 1: update fail"); //error during update query
                }
            }
            else
            {
                return array ('0', $userId);// dont need update, the new score is lower than the old score
            }
        }
        else
        {
            //we need to insert a leaderboard row for the current user
            $result = mysqli_query($this->connection, "INSERT INTO Leaderboard (userId,userName,Highscore) VALUES('$userId','$userName','$score'");
            if ($result)
            {
                return array ('0', $userId); // all OK.
            }
            else
            {
                return array ('2', "Error Code 2: insert fail");//error during leaderboard insertion
            }
        }
    }
        return array('0', $userName);
    }
}

    
//     public function getLeaderboard($Username)
//     {
//         public function getLeaderboard($Username)
// {
//     $result = mysqli_query($this->connection, "SELECT Leaderboard.Username,Leaderboard.score FROM Leaderboard ORDER BY Leaderboard.score DESC");
//     if ($result)
//     {
//         $stack = array();
//         $count = 1;
        
//         $bestScore = 0;
//         $bestPosition = 0;
//         $bestName = null;
//         while (($rowData = mysqli_fetch_row($result)) and ($count < 11)) // retrieves only the top 10 list
//         {
//             $rowUserName=$rowData[0];
//             $rowScore=$rowData[1];
//             array_push($stack, array($count,$rowUserName,$rowScore));
//             if ($rowData[0] == $Username) // if the current userID is in the list, an extra record is returned to show the user info
//             {
//                 $bestName = $rowData[0];
//                 $bestScore = $rowData[1];
//                 $bestPosition = $count;
//             }
            
//             ++$count;
//         }
        
//         array_push($stack, array($bestPosition,$bestName,$bestScore));
//         return array ('0', $stack);
//     }
//     else
//     {
//         return array ('6', "Error Code 6: retrieving list fail");
//     }
// }
//     }      
?>