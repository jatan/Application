$(document).ready(function() {
    $("#sortBudget").change(function() {
        //console.log("Handle for .change method called");
        var element = $("#sortBudget option:selected");
        console.log("Selected Element is: " + element.text());      // Gives content inside the tag <option>
        console.log("Selected Element is: " + element.val());       // Gives value mentioned in value attribute
    });
});
