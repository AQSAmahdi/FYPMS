<style>
div.sidebar{
  width:220px;background:#0C447C;height:100vh;
  display:flex;flex-direction:column;
  position:fixed;left:0;top:0;z-index:999;
  transition:transform .3s ease;
}
.sidebar-logo{
  padding:14px 16px 12px;
  border-bottom:1px solid rgba(255,255,255,.1);
  text-align:center;flex-shrink:0;
}
.sidebar-logo img{width:50px;height:50px;border-radius:50%;border:2px solid rgba(255,255,255,.3);object-fit:cover}
.sidebar-logo p{color:#fff;font-size:12.5px;font-weight:700;margin-top:6px}
.sidebar-logo span{color:rgba(255,255,255,.5);font-size:10.5px}
.sidebar-menu{
  flex:1;overflow-y:auto;overflow-x:hidden;
  padding:8px 0;
  scrollbar-width:none;
}
.sidebar-menu::-webkit-scrollbar{display:none}
.menu-label{
  color:rgba(255,255,255,.35);font-size:9px;font-weight:700;
  letter-spacing:1.8px;text-transform:uppercase;
  padding:0 16px;margin:10px 0 3px;
}
.menu-item{
  display:flex;align-items:center;gap:9px;
  padding:9px 16px;color:rgba(255,255,255,.8);
  text-decoration:none;font-size:12.5px;
  transition:all .2s;border-left:3px solid transparent;
  white-space:nowrap;
}
.menu-item svg{width:14px;height:14px;flex-shrink:0;stroke:rgba(255,255,255,.7);fill:none}
.menu-item:hover,.menu-item.active{background:rgba(255,255,255,.12);color:#fff;border-left-color:#fff}
.menu-item:hover svg,.menu-item.active svg{stroke:#fff}
.menu-item.active{font-weight:600}
.sidebar-footer{
  padding:10px 16px;
  border-top:1px solid rgba(255,255,255,.1);
  flex-shrink:0;
}
.logout-btn{
  display:flex;align-items:center;gap:9px;
  color:rgba(255,255,255,.6);font-size:12.5px;
  cursor:pointer;background:none;border:none;
  width:100%;padding:7px 0;
  transition:color .2s;font-family:'Segoe UI',sans-serif;
}
.logout-btn svg{width:14px;height:14px;stroke:rgba(255,255,255,.6);fill:none}
.logout-btn:hover{color:#fff}
.logout-btn:hover svg{stroke:#fff}
</style>
