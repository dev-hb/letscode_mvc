@content("layout.guest")

<div class="container">
    <div class="content">
        <div class="row">
            <div class="d-block">
                <div class="card">
                    <img src="https://oecdenvironmentfocusblog.files.wordpress.com/2020/06/wed-blog-shutterstock_1703194387_low_nwm.jpg?w=640" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ get("route") }} {{ env('app_name') }}</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
