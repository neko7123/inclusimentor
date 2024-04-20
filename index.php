<?php
session_start();
include("connection.php");
include("function.php");

if($_SERVER['REQUEST_METHOD']== "POST")
{
    //something was posted
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if(!empty($name) && !empty($email) && !empty($message))
    {
        
        $query = "insert into enquiry (name,email,message) values ('$name','$email','$message')";
        
        mysqli_query($con, $query);
        header("Location: index.php");
        die;
    }else
        {
            echo "Enter valid information!";
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LGBTQ+ Mentorship Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:700" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div id="loading">
        <div id="spinner"></div>
    </div>
    <div id="header" class="animated slideInDown" style="animation-delay:1.8s;">
    <div id="title">Welcome to InclusiMentor</div><br>
    <div id="links">
        <a href="#skills">About Us</a>
        <a href="#work" style="margin:0px 60px;">Why Us?</a>
        <a href = "login.php"class="nav-link">Login</a>
        <a href = "signup.php" class="nav-link">Mentee Sign Up</a>
        <a href = "https://forms.gle/ykVxfH9Sak2EW81b6" class="nav-link">Mentor Eligibility Test</a>
    </div>
    </div>

    <center>
    <div id="middle">
        <div id="tagline" class="animated zoomIn" style="animation-delay:1.8s;">
            Empowering LGBTQ+ Students For the Corporate Landscape<br>
            <a href= "signup.php" class="btn_one">Join Us(Mentee)</a><a href= "https://forms.gle/ykVxfH9Sak2EW81b6" class="btn_one">Join Us(Mentor)</a>
        </div>
    </div>
    </center>

    <div id="portfolio">
             <div id="skills">
                 <h1>About Us</h1>   
                 <div>
                        <p>
                            Join a vibrant community dedicated to supporting and nurturing the careers of LGBTQ+ individuals. Our platform is tailored to connect students with industry professionals for invaluable mentorship opportunities, fostering growth, and creating a more inclusive workplace environment. We believe in empowering individuals to overcome barriers, achieve milestones, and flourish professionally within an inclusive community. Together, we create a workplace that embraces diversity, fostering a culture of growth, understanding, and success for all.
                        </p>
                        <p>At InclusiMentor, we believe in the power of mentorship to transform lives and careers. We understand that navigating the professional landscape can be challenging, especially for the vibrant and diverse LGBTQ+ community. That is why we created a platform that goes beyond traditional mentorship, providing a supportive space tailored to the unique experiences of LGBTQ+ Students and Fresh Graduates.</p>
                    <p>Our mission is to empower LGBTQ+ students and fresh graduates by connecting them with mentors who understand the challenges they'll face. We are committed to fostering a culture of inclusivity, where every individual can thrive and succeed in their chosen field.</p>
                    <p>Join Us in Shaping Success, Whether you are a seasoned professional looking to give back or a budding talent seeking guidance, InclusiMentor invites you to join our community. Together, let us forge pathways to success, break down barriers, and create a future where every LGBTQ+ individual can thrive.</p>
                 </div> 
             </div>
         
              <div id="work">
                 <h1>Why Us?</h1>
                 <div class="project">
                 <h2>How Do We Stand Out?</h2>
                 <div>
                    <p>In the competitive world of mentorship platforms, we take pride in its unique focus on connecting LGBTQ+ students with LGBTQ+ professionals and allies for mentorship and professional development for FREE. Recognizing the challenges faced by the community in traditional workspaces, our platform provides a safe haven for mentorship and growth.</p>
                </div>

                </div>

                <div class="project">
                 <h2>Navigating Challenges in the Corporate World</h2>
                 <div>LGBTQ+ individuals often face unique challenges in the workplace, including discrimination, lack of representation, and difficulties in finding mentorship tailored to their experiences. Many struggle to navigate corporate environments without a support system that understands and appreciates their journey.
                 </div>
                </div>

                <div class="project">
                    <h2>Transformative Mentorship for LGBTQ+ Excellence</h2>
                    <div>This is not just a mentorship platform; it is a catalyst for positive change. By offering a space for LGBTQ+ students to create comprehensive profiles that showcase their skills, experiences, and identity, we ensure that mentorship is not only tailored to industry goals but also reflects the unique challenges and triumphs of the LGBTQ+ community.</div>
                   </div>

                <div class="project">
                    <h2>Preparing for Success in the Professional Arena</h2>
                    <div>Our platform equips individuals with the tools needed to thrive in the corporate world. Through personalized mentorship, we address specific challenges faced by the LGBTQ+ community, helping mentees build resilience, navigate their careers confidently, and achieve their professional goals.
                    </div>
                   </div>

                <div class="project">
                    <h2>Elevating Potential Through Guided Growth</h2>
                    <div>Mentorship is the cornerstone of professional development. At InclusiMentor, mentors guide mentees through personalized strategies, offering insights, advice, and a supportive network. As a mentee on our platform, you will not only gain industry knowledge but also build essential skills and confidence for a successful career.</div>
                </div>

                <div class="project">
                    <h2>Our Commitment To Your Privacy </h2>
                    <div>At InclusiMentor, we prioritize and respect the privacy of your data. We neither store nor retain any of your personal information thereafter. Your trust is paramount to us, and we are committed to maintaining the confidentiality of your data. </div>
                </div>

             </div>
         
              <div id="contact">
                 <h1>Contact Us Or Report a Problem</h1> 
                 <form method="post">
                     <input type="text" name="name" placeholder="name" required>
                     <input type="email" name="email" placeholder="email" required><br>
                     <input type="text" name="message" placeholder="your message" required rows="5"></input><br>
                     <input type="submit" value="send" class="btn_one"><a href= "#middle" class="btn_one">Join Us</a>
                 </form>
                 <div id="details">
                    <a class="btn_social"><i class="fas fa-phone"></i></a>
                    <a class="btn_social"><i class="fas fa-at"></i></a>
                    <a class="btn_social"><i class="fab fa-twitter"></i></a>
                    <a class="btn_social"><i class="fab fa-dribbble"></i></a>
                 </div>   
             </div>
    </div>
    <div id="footer">
        Unlock Your Potential in an Inclusive Community <br><br> <a href="">Made as a part of YCFP 2023 Program</a>
    </div>
<script src="index.js" type="text/javascript"></script>
</body>
</html>