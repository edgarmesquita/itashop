var timeCount = function(nY, nM, nD, nH, nMn, nS)
{
    var df = new Date(nY, nM-1, nD, nH, nMn, nS);
    var t = new Date(df - currentTime);

    var h = t.getUTCHours();
    var m = t.getUTCMinutes();
    var s = t.getUTCSeconds();

    if(currentTime >= df) h=m=s=0;

    $("#hour").text(h<10?"0"+h:h);
    $("#minute").text(m<10?"0"+m:m);
    $("#second").text(s<10?"0"+s:s);

    currentTime.setSeconds(currentTime.getSeconds()+1);
    if(h == 0 && m == 0 && s == 0) return;
    else setTimeout("timeCount("+nY+", "+nM+", "+nD+", "+nH+", "+nMn+", "+nS+")",1000);
}