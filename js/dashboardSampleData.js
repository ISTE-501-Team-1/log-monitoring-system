var sampleDataString = "" +
    "<thead>" +
    "<tr>" +
    "<th>Student ID</th>" +
    "<th>Student First Name</th>" +
    "<th>Student Middle Initial</th>" +
    "<th>Student Last Name</th>" +
    "<th>Student Username</th>" +
    "<th>Student School</th>" +
    "</tr>" +
    "</thead>\n" +

    "<tr>" +
    "<th>1</th>" +
    "<th>Ryan</th>" +
    "<th>N</th>" +
    "<th>Vay</th>" +
    "<th>rxv7131</th>" +
    "<th>CIS</th>" +
    "</tr>\n" +

    "<tr>" +
    "<th>2</th>" +
    "<th>Evan</th>" +
    "<th>M</th>" +
    "<th>Vay</th>" +
    "<th>EvanUsername123</th>" +
    "<th>CS</th>" +
    "</tr>\n";



function getSampleData(){
    return sampleDataString;
}


window.onload = function(){
    $("#studentTable").append( sampleDataString );
};
