<div class="container container-wrapper">
    <header class="header">
        <div class="top-box" data-toggle="sticky-onscroll"><!-- head -->
            <div class="container">

                <section class="header-inner">
                    <div class="container">
                        <?php include "templates/head/nav/_logos.php"?>
                    </div>
                </section>

            </div>
        </div>
    </header>

    <main class="main section-color-primary">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- /. widget-AVAILABLE PACKAGES -->
                    <div class="row">
                        <div class="col-lg-6" style="float:none;margin:auto;"><!--center-->
                            <div class="widget  widget-box box-container widget-form form-main" id="form">
                                <div class="widget-header">
                                    <h2>Iniciar Sesión</h2>
                                </div>

                                <form method="post" action="./index.php?seccion=adm_session&accion=loguea" ><!-- form -->
                                    <div class="control-group">
                                        <label class="control-label" for="inputUsername2">Nombre de usuario</label>
                                        <div class="controls">
                                            <input type="text" name='user' class="form-control" id="user" placeholder="Usuario" required/>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inputPassword1">Contraseña</label>
                                        <div class="controls">
                                            <input type="password" name='password' class="form-control" id="password" placeholder="Password" required />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember" id="remember" value="true" /> Recordar </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <div class="controls">
                                            <button type="submit" class="btn btn-danger">Loguea</button>
                                            <button type="reset" class="btn btn-default">Limpiar</button><br>
                                            <a href="#"><em>¿Olvidaste tu contraseña?</em></a>
                                        </div>
                                    </div>
                                </form>

                            </div><!-- /.widget-form-->
                        </div>
                    </div>
                </div><!-- /.center-content -->
            </div>
        </div>
    </main><!-- /.main-part-->

<footer class="footer">
    <div class="container footer-mask">
        <div class="container footer-contant">
            <div class="row">
                <div class="col-md-3 hidden-sm hidden-xs"><!--Div img-->
                    <div class="logo"><a href="#"><img src="assets/img/icons/Icono-TIQUE.png" alt="" /></a></div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="title">
                        <h4>Contacto</h4>
                    </div>
                    <ul class="list list-contact  list-news">
                        <li>Av. Vallarta 6503 Int. H-12,
                            Col. Ciudad Granja 45010 Zapopan,
                            Jalisco, México</li>
                        <li><i class="fa fa-phone"></i> +52 1 33 3677 7841</li>
                        <li><i class="fa fa-phone"></i>  +52 1 33 1605 2732</li>
                        <li><i class="fa fa-envelope"></i>  contactame@inmobiliariatique.com</li>
                    </ul>
                </div>
            </div>
        </div><!-- /.footer-content -->
    </div>
</footer>
</div>

