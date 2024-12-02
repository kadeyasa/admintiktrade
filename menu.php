<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Admin Panel - TRD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          	<a class="nav-link active" aria-current="page" href="index.php">
				<i class="bi bi-speedometer2"></i>&nbsp;
				Dashboard
			</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-wallet2"></i>&nbsp;Members
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="member.php">Member List</a></li>
            <li><a class="dropdown-item" href="admin-pro.php">Upgraded Member</a></li>
            <li><a class="dropdown-item" href="admin-geonology.php">Geonology</a></li>
            <li><a class="dropdown-item" href="admin-turnover.php">Member Turnover</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-wallet2"></i>&nbsp;Wallet
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="admin-deposit.php">Deposit</a></li>
            <li><a class="dropdown-item" href="admin-withdraw.php">Withdraw</a></li>
            <li><a class="dropdown-item" href="admin-rewards.php">Rewards</a></li>
            <li><a class="dropdown-item" href="admin-recordenergy.php">Record Energy</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-wallet2"></i>&nbsp;Energy
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="admin-energy.php">Member Energy</a></li>
			      <li><a class="dropdown-item" href="admin-cut-balance.php">Cut Balance</a></li>
          </ul>
        </li>
        <?php if($_SESSION['role']==1){?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-wallet2"></i>&nbsp;Setting
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="energy-package.php">Energy Package</a></li>
            <li><a class="dropdown-item" href="bonus-setup.php">Bonus Setup</a></li>
            <li><a class="dropdown-item" href="admin-setting.php">System Setting</a></li>
            <li><a class="dropdown-item" href="admin-membership.php">Membership Setting</a></li>
            <li><a class="dropdown-item" href="admin-reward-setting.php">Reward Setting</a></li>
            <li><a class="dropdown-item" href="add-reward.php">Add Reward</a></li>
            <li><a class="dropdown-item" href="add-balance.php">Add Balance</a></li>
          </ul>
        </li>
        <?php }?>
        <li class="nav-item">
          	<a class="nav-link" aria-current="page" href="logout.php">
				<i class="bi bi-box-arrow-left"></i>&nbsp;
				Logout
			</a>
        </li>
      </ul>
    </div>
  </div>
</nav>