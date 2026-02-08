<?php
$homeUrl = '';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $homeUrl .= "https://" . $_SERVER['HTTP_HOST'] . "/";
} else {
    $homeUrl .= "http://" . $_SERVER['HTTP_HOST'] . "/sami-art/";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap"
        rel="stylesheet">

    <style>
        body {
            height: 100dvh;
            width: 100dvw;
            overflow: hidden;
            font-family: Inter;
        }

        #canvas1 {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        a {
            color: #103a31;
        }
    </style>
</head>

<body>
    <div class="container" style="text-align: center;">
        <canvas id="canvas1"></canvas>
        <h1>Page Not Found</h1>
        <p>The page you are looking for does not exist.</p>
        <a href="<?php echo $homeUrl; ?>" style="text-align: center; font-style: italic;">Go to Home</a>
    </div>

    <script>
        window.addEventListener("load", function () {
            const canvas = document.getElementById("canvas1");
            const ctx = canvas.getContext("2d", {
                willReadFrequently: true
            });

            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight - 100;

            class Particle {
                constructor(effect, x, y, color) {
                    this.effect = effect;
                    this.x = Math.random() * this.effect.canvasWidth;
                    this.y = this.effect.canvasHeight;
                    this.originX = x;
                    this.originY = y;
                    this.size = this.effect.gap;
                    this.color = color;
                    this.dx = 0;
                    this.dy = 0;
                    this.vx = 0;
                    this.vy = 0;
                    this.force = 0;
                    this.angle = 0;
                    this.distance = 0;
                    this.friction = Math.random() * 0.6 + 0.15;
                    this.ease = Math.random() * 0.1 + 0.005;
                }
                update() {
                    this.dx = this.effect.mouse.x - this.x;
                    this.dy = this.effect.mouse.y - this.y;
                    this.distance = this.dx * this.dx + this.dy * this.dy;
                    this.force = -this.effect.mouse.radius / this.distance;
                    if (this.distance < this.effect.mouse.radius) {
                        this.angle = Math.atan2(this.dy, this.dx);
                        this.vx += this.force * Math.cos(this.angle);
                        this.vy += this.force * Math.sin(this.angle);
                    }
                    this.x +=
                        (this.vx *= this.friction) + (this.originX - this.x) * this.ease;
                    this.y +=
                        (this.vy *= this.friction) + (this.originY - this.y) * this.ease;
                }
                draw() {
                    this.effect.context.fillStyle = this.color;
                    this.effect.context.fillRect(this.x, this.y, this.size, this.size);
                }
            }

            class Effect {
                constructor(context, canvasWidth, canvasHeight) {
                    this.context = context;
                    this.canvasWidth = canvasWidth;
                    this.canvasHeight = canvasHeight;
                    this.particles = [];
                    this.gap = 2;
                    this.mouse = {
                        radius: 20000,
                        x: 0,
                        y: 0
                    };

                    window.addEventListener("mousemove", (e) => {
                        this.mouse.x = e.x;
                        this.mouse.y = e.y;
                    });

                    window.addEventListener("touchmove", (e) => {
                        const touch = e.touches[0];
                        this.mouse.x = touch.clientX;
                        this.mouse.y = touch.clientY;
                    });

                    this.autoMove = true;

                    setInterval(() => {
                        if (this.autoMove) {
                            this.mouse.radius = 2000;
                            const centerX = canvas.width / 2;
                            const centerY = canvas.height / 2;
                            const rangeX = 300; // horizontal spread
                            const rangeY = 150; // vertical spread

                            effect.mouse.x = centerX - rangeX / 2 + Math.random() * rangeX;
                            effect.mouse.y = centerY - rangeY / 2 + Math.random() * rangeY;

                            setTimeout(() => {
                                // reset mouse position to center
                                effect.mouse.x = 0;
                                effect.mouse.y = 0;
                                this.mouse.radius = 20000;
                            }, 100)
                        }
                    }, 4000);
                }

                loadSVG(svgString) {
                    const img = new Image();
                    img.src = "data:image/svg+xml;base64," + btoa(svgString);

                    img.onload = () => {
                        // Clear canvas
                        this.context.clearRect(0, 0, this.canvasWidth, this.canvasHeight);

                        // Draw SVG centered
                        const scale = 2; // adjust scale if needed
                        const x = (this.canvasWidth - img.width * scale) / 2;
                        const y = (this.canvasHeight - img.height * scale) / 2;
                        this.context.drawImage(
                            img,
                            x,
                            y,
                            img.width * scale,
                            img.height * scale
                        );

                        // Convert image pixels to particles
                        this.convertToParticles();
                    };
                }

                convertToParticles() {
                    this.particles = [];
                    const pixels = this.context.getImageData(
                        0,
                        0,
                        this.canvasWidth,
                        this.canvasHeight
                    ).data;
                    for (let y = 0; y < this.canvasHeight; y += this.gap) {
                        for (let x = 0; x < this.canvasWidth; x += this.gap) {
                            const index = (y * this.canvasWidth + x) * 4;
                            const alpha = pixels[index + 3];
                            if (alpha > 0) {
                                const red = pixels[index];
                                const green = pixels[index + 1];
                                const blue = pixels[index + 2];
                                const color = "rgb(" + red + "," + green + "," + blue + ")";
                                this.particles.push(new Particle(this, x, y, color));
                            }
                        }
                    }
                    this.context.clearRect(0, 0, this.canvasWidth, this.canvasHeight);
                }

                render() {
                    this.particles.forEach((particle) => {
                        particle.update();
                        particle.draw();
                    });
                }
            }

            let effect = new Effect(ctx, canvas.width, canvas.height);

            // Your SVG string
            const svgString = `<svg xmlns="http://www.w3.org/2000/svg" width="800" height="300" fill="#103a31">
  <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-weight="700" font-size="200" font-family="Inter">
    404
  </text>
</svg>`;

            effect.loadSVG(svgString);

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                effect.render();
                requestAnimationFrame(animate);
            }
            animate();

            window.addEventListener("resize", function () {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight - 100;
                effect = new Effect(ctx, canvas.width, canvas.height);
                effect.loadSVG(svgString);
            });
        });
    </script>
</body>

</html>