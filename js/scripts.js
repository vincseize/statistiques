function toggleContent() {
    // Get the DOM reference
    var contentIdDb = document.getElementById("resultats_database");
    var contentIdAgrandir = document.getElementById("div_bt_agrandir");
    // Toggle 
    contentIdDb.style.display == "block" ? contentIdDb.style.display = "none" : contentIdDb.style.display = "block"; 
    contentIdAgrandir.style.display == "block" ? contentIdAgrandir.style.display = "none" : contentIdAgrandir.style.display = "block"; 
}
function increaseFontSize(objId, plusOUmoins) {
    obj = document.getElementById(objId);
    currentSize = parseFloat(obj.style.fontSize);
    if(plusOUmoins=='+'){
        obj.style.fontSize = (currentSize + .1) + "em";
    }
    if(plusOUmoins=='-'){
        obj.style.fontSize = (currentSize - .1) + "em";
    }
}

function VIDER_TABLES() {
    window.location.href='index.php?VAR_VIDER_TABLES';
    $(loader).show();
}

function RELOAD() {
    window.location.href='index.php';
    $(loader).show();
}
