<div id="addingNewQuestionBase" class="emi-modal">
    <div class="emi-modal-title">New question base 
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">
        <form name="addQuestionBase" action="indexQuestionBase.php" method="POST">
            <input type='hidden' name='choosenSubjectId'/>
            <table id="pointsTable">
                <tbody>
                    <tr>
                        <td>Title for the base</td>
                        <td colspan="2"><input type="text" name="qbase-title"/><br></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="possiblePointsText">No possible points for this base</div>
                        </td>
                    </tr>
                </tbody>
                <tbody id="pointsTable_inputContainer">
                    <tr style="display: none;" id="pointsTrTemplate" class="qbaseDiv">
                        <td><input type="text" class="qbasePoints" /></td>
                        <td>Points</td>
                        <td><button class="pointRemoveBtn" type='button'>Remove</button></td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td><button id="addPoints">Add points</button></td>
                        <td colspan="2"><input class="submit-on-right submitQbase" type="submit" name="add-new-qbase" value="submit"/></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<div id="addingNewQuestion" class="emi-modal">
    <div class="emi-modal-title">New question
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 400px; overflow-y: auto;">
        <form name="addQuestion" action="indexQuestionBase.php" method="POST" class="addinQuestionForm">
            <div class='addQuestionMain'>
                <input type='hidden' name='chosenQbaseId'/>
                <div style="padding-bottom: 10px;">Input a question for:
                    <select name='points' style="display: inline-block; margin-left: 20px;" class="pointsSelect">
                    </select>points
                </div>
                <div class="wrongAnswer">
                    Each wrong answer will cost the user: <input type="text" name="wrongAnswerCost"/> %
                </div>
                
                <span style="margin: 0; padding:0;">Question:</span><br>
                <textarea rows="4" cols="50" name="questionContent" style="margin: 0px 0px 15px 0px; padding:0;" ></textarea>
                <div id="answersTable">
                    <div id="answersTable_inputContainer" > 
                        <div id="answersTable_answersText">There are no answers </div>
                        <div style="display: none;" id="answersTrTemplate" class='answerDiv'>
                            <div class='answersTable'>
                                <div class='answersCounter' style='float: left;'></div>
                                <div style='margin-left: 20px;'>
                                    <textarea rows="3" name="answers" class="questionContent" style="width: 100%; margin: 0; padding:0;"></textarea>
                                    <button class="answerRemoveBtn" type='button' style='display: block; float: right; position: relative; top: -2px;'>Remove</button>
                                    <label><input class="checkContent" name="correctAnswerCheck" type="checkbox" value='1'/> Correct answer</label>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>

                <input class="submit-on-right submitQuestion" type="submit" name="add-new-question" value="submit"/>
                <a href="#" id="addAnswers" class="align-right"><button>Add answer</button></a>

            </div>
            <div class='addQuestionLoadingGif' style='position: absolute; top: 60px; left: 60px;'>
                <img src='../images/loading.gif' style='width: 200px; height: 200px;' />
            </div>
        </form>
    </div>
</div>


