<?php

if (!$app->diceSession->has("diceGame")) {
    $app->diceSession->set("diceGame", new \Vibe\Dice\Dice());
    $diceGame = $app->diceSession->get("diceGame");
} else {
    $diceGame = $app->diceSession->get("diceGame");
}

$check = $diceGame->checkRolls();
$winner = $diceGame->checkWinner();
$player = $diceGame->checkTurn();

if (isset($_POST["roll"])) {
    $diceGame->roll();
    $app->diceSession->set("diceGame", $diceGame);
}

if (isset($_POST["save"])) {
    $diceGame->getTotal();
    $app->diceSession->set("diceGame", $diceGame);
}

if (isset($_POST['reset'])) {
    $app->diceSession->destroy();
}

?>

<div id="section-one">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Total Score</div>
                    <div class="panel-body">
                        <p>Player 1: <?= $diceGame->scorePlayerOne ?></p>
                        <hr>
                        <p>Player 2: <?= $diceGame->scorePlayerTwo ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Current Player <?= $player ?></div>
                    <div class="panel-body">
                        <h3>Rolls: <?= implode(" ", $diceGame->returnList())?></h3>
                        <h5>Status: <?= $check ? $check : $winner ?></h5>
                        <hr>
                        <form class="form" action="" method="POST">
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="submit" class="btn btn-success" value="roll" name="roll" <?= $winner ? 'disabled' : '' ?>>Roll</button>
                                <button type="submit" class="btn btn-default" value="save" name="save" <?= $winner ? 'disabled' : '' ?>>Save</button>
                                
                            </div>
                            <button type="submit" class="btn btn-danger pull-right" value="reset" name="reset">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>