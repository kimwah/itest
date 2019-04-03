<html>

<head>
    <title>Test</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>    
    <script>
        function formValidate(){
            var reg = /^\d+$/;
            if(!reg.test($('#playerNo').val())) {
                alert("Input value does not exist or value is invalid");
                return false;
            }
        }
    </script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<div class="container">
    <form method="post" onSubmit="return formValidate();" class="form-inline">
    <div class="form-row align-items-center mt-3">
        <label for="playerNo">Number Of Player: </label>        
        <input type='text' maxLength="2"  class="form-control" name='playerNo' id='playerNo' value="<?php echo $_POST['playerNo']; ?>"> &nbsp;&nbsp;
        <input type="submit"  class="btn btn-primary" value="Generate Output" name="btnGenerate" id="btnGenerate" />
        <div class="container">
        <p class="text-secondary"><small> ( to show case the error handling, test with value "zero" )</small></p>
        </div>
        <?php
        class Game
        {
            private $aryCards = [];
            private $errorMsg = '';
            function __construct()
            {
                $this->buildCards();
            }
            function buildCards()
            {
                $aryShapes = ['S', 'H', 'D', 'C'];
                $aryNo = ['A', 2, 3, 4, 5, 6, 7, 8, 9, 'X', 'J', 'Q', 'K'];
                foreach ($aryShapes as $aShape) {
                    foreach ($aryNo as $aNo) {
                        $this->aryCards[] = "{$aShape}-{$aNo}";
                    }
                }
                shuffle($this->aryCards);
            }
            /**
             * Start Game
             * @param number $pax number of players
             */
            function startGame($pax)
            {
                $cardOnHand = [];
                if ((!is_numeric($pax)) || ($pax < 1)) {
                    $this->errorMsg = "Input value does not exist or value is invalid";
                    return false;
                }
                $round = floor(52 / $pax);

                // If the pax is more then the number of cards available, we can only distribute 1 round
                // why not useing 52 and use "count($this->aryCards", to safeguard the code for later change in buildCard process
                if ($pax > count($this->aryCards)) $round = 1;

                for ($i = 0; $i < $round; $i++) {
                    for ($p = 0; $p < $pax; $p++) {
                        $card = array_shift($this->aryCards);
                        $cardOnHand[$p][] = $card;
                    }
                }
                return $cardOnHand;
            }
            /**
             * To get the last error
             */
            function getError()
            {
                return $this->errorMsg;
            }
        }

        $pax = $_POST['playerNo'];
        $strHtml = '';

        if ($_POST['btnGenerate']) {
            $theGame = new Game();
            $playerCards = $theGame->startGame($pax);
            if ($playerCards) {
                $strHtml = "<h3>Result Output</h3>";
                foreach ($playerCards as $aRow) {
                    $strRow = implode(',', $aRow);
                    /**
                     * $strRow is holding the a player cards
                     * Due to the Test paper mentioned that cannot use LF for display,
                     * I will use <br/> to break them into lines
                     */
                    $strHtml .= $strRow . '<br/>';
                }
                $strHtml = '<div class="jumbotron mt-3">'.$strHtml.'</div>';
            } else {
                $strHtml = '<div class="alert alert-danger mt-4" role="alert">'.$theGame->getError().'</div>';
            }
        }
        echo "<div class=\"container\">";
        echo $strHtml;
        echo "</div>";
        ?>
        </div>
    </form>
</div>    
</body>

</html> 