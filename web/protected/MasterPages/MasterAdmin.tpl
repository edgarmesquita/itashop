<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <com:THead Title="Clube Itashop [Administração]">
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="DC.format" content="text/xhtml" />

        <com:TStyleSheet StyleSheetUrl="resources/css/colorpicker.css" MediaType="all" />
        <com:TStyleSheet StyleSheetUrl="resources/css/spry/SpryMenuBarHorizontal.css" MediaType="all" />
        <com:TStyleSheet StyleSheetUrl="resources/css/global.css" MediaType="all" />
        <com:TStyleSheet StyleSheetUrl="resources/css/admin.css" MediaType="all" />
        
        <script type="text/javascript" src="resources/js/jquery.js"></script>
        <script type="text/javascript" src="resources/js/colorpicker.js"></script>
        <script type="text/javascript" src="resources/js/eye.js"></script>
        <script type="text/javascript" src="resources/js/utils.js"></script>
        <script language="javascript" type="text/javascript" src="resources/js/spry/SpryMenuBar.js"></script>
        
        <script type="text/javascript" src="resources/js/admin.js"></script>
        <com:TContentPlaceHolder ID="cphHeader" />
    </com:THead>
    <body>
        <com:TForm>
            <div id="all">
                <div id="header">
                    <h1>Clube Itashop</h1>
                    <p class="tab"><span><com:TLabel ID="lblNome" /> |
                        <com:TLinkButton Text="Logout" OnClick="logoutButtonClicked" Visible="<%= !$this->User->IsGuest %>" CausesValidation="false" /></span></p>
                </div>
                <div id="menu">
                    <com:TRepeater ID="rptMenu" OnItemDataBound="repeaterDataBound">
                        <prop:HeaderTemplate><ul id="menuList" class="MenuBarHorizontal"></prop:HeaderTemplate>
                        <prop:ItemTemplate>
                            <com:TLiteral ID="ltMenu" />
                        </prop:ItemTemplate>
                        <prop:FooterTemplate></ul></prop:FooterTemplate>
                    </com:TRepeater>
                </div>
                <div id="content">
                    <com:TContentPlaceHolder ID="cphMain" />
                </div>
                <div id="footer">2010 Itashop. Todos os direitos reservados</div>
            </div>
        </com:TForm>
    </body>
</html>