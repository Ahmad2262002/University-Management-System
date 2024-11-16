<style>
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: flex-start; 
        align-items: center;
        background-color: #1c1c1e;
        padding: 10px 20px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .navbar a {
        background-color: #007aff;
        color: white;
        padding: 10px 20px;
        border-radius: 15px;
        text-decoration: none;
        transition: background-color 0.3s;
        margin-right: 10px; 
    }

    .navbar a:last-child {
        margin-right: 0; 
    }

    .navbar a:hover {
        background-color: #005cbf;
    }
</style>
<div class="navbar">
    <a class="nav-link" href="AdminCp.php" >Back</a>
    <a href="OpenFile.php">New Student</a>
</div>
