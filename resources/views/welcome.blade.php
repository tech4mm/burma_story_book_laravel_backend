<!DOCTYPE HTML>
<html>

<head>
    <title>Tech4MM</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css" />
    </noscript>
</head>

<body class="is-preload">
    <div id="wrapper">
        <div id="bg"></div>
        <div id="overlay"></div>
        <div id="main">

            <!-- Header -->
            <header id="header">
                <h1>Tech4MM</h1>
                <p>Professional App Development &nbsp;&bull;&nbsp; Professional Web Development &nbsp;&bull;&nbsp;
                    Professional IT Trainings</p>
                <nav>
                    <ul>
                        <li><a href="https://www.facebook.com/tech4mm.co.ltd" class="icon brands fa-facebook-f"
                                target="_blank" rel="noopener noreferrer"><span class="label">Facebook</span></a>
                        </li>
                        <li><a href="https://github.com/tech4mm" class="icon brands fa-github" target="_blank"
                                rel="noopener noreferrer"><span class="label">Github</span></a></li>
                        <li><a href="mailto:info@tech4mm.com" class="icon solid fa-envelope"><span
                                    class="label">Email</span></a></li>
                    </ul>
                </nav>
            </header>

            <!-- Footer -->
            <footer id="footer">
                <span class="copyright"> Copyright &copy; <span id="year"></span> Tech4MM Company Limited. </span>
            </footer>

        </div>
    </div>
    <script>
        window.onload = function() {
            document.body.classList.remove('is-preload');
        }
        window.ontouchmove = function() {
            return false;
        }
        window.onorientationchange = function() {
            document.body.scrollTop = 0;
        }
    </script>
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>

</html>

