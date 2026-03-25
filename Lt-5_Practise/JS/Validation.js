console.log("Connected");
function analyze_text()
{
    let text = document.getElementById("textInput").value;
    if(text.trim() == "")
    {
        document.getElementById("result").innerHTML ="Please enter some text!";
        return false;
    }
    let charCount = text.length;
    let arr = text.split(" ");
    let wordCount = 0;
    for(let i = 0; i < arr.length; i++)
    {
        if(arr[i] != "")
        {
            wordCount++;
        }
    }
    let reversedText = "";
    for(let i = text.length - 1; i >= 0; i--)
    {
        reversedText = reversedText + text[i];
    }
    document.getElementById("result").innerHTML ="Total Characters: " + charCount + "<br>" +"Total Words: " + wordCount + "<br>" +"Reversed Text:" + reversedText;
    return false;
}