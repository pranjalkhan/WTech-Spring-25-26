console.log("Connected");
let clickcount =0;

var a = ["ABC", "DEF"];
a.forEach((item,index)=>{
    console.log("Index:", index, "Item: ", item);
})
a.map((item, index)=>{
    console.log("Index:", index, "Item: ", item);
})
function get_name()
{
    let paname = document.getElementById("PatientName").value;
    document.getElementById("paname").style.color="red";
    console.log(paname);
    return false;
}
function get_age()
{
    let page= document.getElementById("Age").value;
    document.getElementById("page").style.color="red";
    console.log(page);
    return false;
}
function get_textarea()
{
    let paddress= document.getElementById("Address").value;
    console.log(paddress);
    return false;
}

function collect_data()
{
    clickcount++;
    let submit = document.getElementById("submitdata").value;
    document.getElementById("submitdata").style.color="red";
    document.getElementById("submitdata").innerHTML="Data Submit: "+clickcount;

    return false;
}