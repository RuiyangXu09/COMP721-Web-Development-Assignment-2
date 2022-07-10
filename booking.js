/**
 * this file will deal with the booking function via post method to receive data
 * Author: RuiyangXu 18017865
 */
var xhr = createRequest();

//function deal with booking details
function getBookingData(dataSource, divId, aName, aPhone, aUnumber, aSnumber, aStName, aSbName, aDsbName, aDate, aTime)
{
    if (xhr)
    {
        var obj = document.getElementById(divId);
        var requestBody = "cname="+encodeURIComponent(aName)+"&phone="+encodeURIComponent(aPhone)+"&unumber="+encodeURIComponent(aUnumber)
                         +"&snumber="+encodeURIComponent(aSnumber)+"&stname="+encodeURIComponent(aStName)+"&sbname="+encodeURIComponent(aSbName)
                         +"&dsbname="+encodeURIComponent(aDsbName)+"&date="+encodeURIComponent(aDate)+"&time="+encodeURIComponent(aTime);
        xhr.open("POST", dataSource, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function ()
        {
            obj.innerHTML = xhr.responseText;
        }
        xhr.send(requestBody);
    }
}