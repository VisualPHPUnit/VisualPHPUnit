<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>Main</title>
		<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<link rel="stylesheet" href="css/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
	</head>
	<body>
		<div id="container">
			
			<div class="box rounded">
				<div class="testsuite failure">
					<div class="light rounded"></div>
					<div class="name">TagTestCase</div>
					<div class="stats">useful stats</div>
					<div class="expand button">-</div>
				</div>
				<div class="more hide">
					<hr class = "big" />
					<div class="test failure">
						<div class="light rounded"></div>
						<div class="name">test_one</div>
						<div class="stats">useful stats</div>
						<div class="expand button">+</div>
						<div class="more test show">
							<div class="variables">
								Variables
							</div>
							<div class="stacktrace">
								Stack Trace
							</div>
						</div>
					</div>
					<hr class = "small" />
					<div class="test success">
						<div class="light rounded"></div>
						<div class="name">test_two</div>
						<div class="stats">useful stats</div>
						<div class="expand button">-</div>
					</div>
					<hr class = "small" />
					<div class="test failure">
						<div class="light rounded"></div>
						<div class="name">test_three</div>
						<div class="stats">useful stats</div>
						<div class="expand button">+</div>
					</div>
				</div>
			</div>
			<div class="box rounded">
				<div class="testsuite failure">
					<div class="light rounded"></div>
					<div class="name">TagTestCase</div>
					<div class="stats">useful stats</div>
					<div class="expand button">-</div>
				</div>
				<div class="more_testsuite hide">
					<hr class = "big" />
					<div class="test failure">
						<div class="light rounded"></div>
						<div class="name">test_one</div>
						<div class="stats">useful stats</div>
						<div class="expand button">+</div>
						<div class="more_test show">
							<div class="variables">
								Variables
							</div>
							<div class="stacktrace">
								Stack Trace
							</div>
						</div>
					</div>
					<hr class = "small" />
					<div class="test success">
						<div class="light rounded"></div>
						<div class="name">test_two</div>
						<div class="stats">useful stats</div>
						<div class="expand button">-</div>
					</div>
					<hr class = "small" />
					<div class="test failure">
						<div class="light rounded"></div>
						<div class="name">test_three</div>
						<div class="stats">useful stats</div>
						<div class="expand button">+</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script src="/Javascript/jquery/jquery.js" type="text/javascript" charset="utf-8"></script>
	<script src="javascript/main.js" type="text/javascript" charset="utf-8"></script>
</html>