jQuery(function()
{
    var menuBar = new Spry.Widget.MenuBar("menuList", {imgDown:"resources/images/spry/SpryMenuBarDownHover.gif", imgRight:"resources/images/spry/SpryMenuBarRightHover.gif"});
});

var nonZero = function(sender, parameter)
{
    if(parameter > 0) return true;
    return false;
}