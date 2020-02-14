
<!DOCTYPE html>
<html>
<!--=====================================
   Here Document Heder Template
 ======================================-->  
 {include file="htmlConfig/header.tpl"}

<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <!-- =============================================
     Here Document Top Nav Bar Template
    ============================================= -->

    {include file="htmlConfig/topnavbar.tpl"}

  <!-- =============================================== -->

    <!--=============================================
                    MENU
    =============================================-->

    {include file="htmlConfig/menu.tpl"}

    <!--=============================================
                BEGIN MAIN CONTENT
    =============================================-->

      {block name="content"}  {/block}

    <!-- =============================================
                END MAIN CONTENT
    =============================================-->


     <!--=====================================
       Here Document Footer Template
     ======================================-->  
     {include file="htmlConfig/footer.tpl"}


  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


