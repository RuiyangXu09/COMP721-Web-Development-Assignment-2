/**
 * this file will deal with the search and assign function via post method to receive data
 * Author: RuiyangXu 18017865
 */
var xhr = createRequest();

//function deal with search data
function SearchData(dataSource, divId, aBookingNumber)
{
    if (xhr)
    {
        var obj = document.getElementById(divId);
        var requestBody = "search="+encodeURIComponent(aBookingNumber);
        xhr.open("POST", dataSource, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function ()
        {
            obj.innerHTML = xhr.responseText;
        }
        xhr.send(requestBody);
    }
}

//function deal with Assign Order data
function assignOrder(dataSource, divId, assign,aBookingNumber)
{
    if (xhr)
    {
        var obj = document.getElementById(divId);
        var requestBody = "assign="+encodeURIComponent(assign)+"&search="+encodeURIComponent(aBookingNumber);
        xhr.open("POST", dataSource, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function ()
        {
            obj.innerHTML = xhr.responseText;
        }
        xhr.send(requestBody);
    }
}