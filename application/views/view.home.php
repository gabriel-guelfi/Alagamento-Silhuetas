<div id="view-home-contents">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="introduction">
				<h4>Introduction</h4>
				<p>
					This tool predicts total areas that would be <a href="/application/views/media/img/sample-flooded.jpeg" target="_blank">flooded</a>, based on a 2D <a href="/application/views/media/img/sample-map.png" target="_blank">silhouettes map</a>, which is generated from an integer matrix. The matrx is a ".txt" input file.
					<br>
					<br>
					File's content format must follow this rules:
				</p>
				<ul>
					<li>First line: An integer that represents the quantity of cases to be analized.</li>
					<li>Secong line: A blank space or an intenger that represents the length of the matrix, in other words, the number of silhouttes of the case.</li>
					<li>Third line: A blank space or a sequence of integers separated by blank spaces. Each integer represents the height of the silhouette.</li>
				</ul>
				<p>
					You can repeat lines 2 and 3 for each case you want to include in the calculation. <a href="/Datasets/input-dataset-file1.txt" target="_blank">See an example.</a>
				</p>
			</div>
			<div class="form-container">
				<h4>Controls</h4>
				<form action="/?execute=1" method="POST" enctype="multipart/form-data">
					<input required id="input-file" type="file" name="file" accept=".txt">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12">
							<input id="bt-choose-file" type="button" value="Choose Input File">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12">
							<div class="row">
								<div id="radio-input-container">
									<div class="col-lg-12 col-md-12 col-sm-12">
										<input id="directdownload" type="radio" name="output-option" value="1" checked> <label for="directdownload">Directly download result file</label>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<input id="writefile" type="radio" name="output-option" value="2"> <label for="writefile">Write result file to disk</label>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<input id="showresponse" type="radio" name="output-option" value="3"> <label for="showresponse">Show result on the screen</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12">
							<input type="submit" value="Calculate">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-12">
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12">
			<?php
			if(!empty($_SESSION['response-data'])){
				echo '<div id="response-screen">';
				echo '<h4>Results:</h4>';
				foreach($_SESSION['response-data'] as $k => $r){
					echo '<span>&#8627; Case '.($k+1).': <span class="response-val">'.$r.'</span></span><br><br>';
					unset($_SESSION['response-data'][$k]);
				}
				echo '</div>';
			}
			?>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-12">
		</div>
	</div>
</div>
<style>
#view-home-contents, #view-home-contents *{
	text-align: center;
	padding:1%;
}
.form-container{
	background-color: #eee;
	border-radius:5px;
	box-shadow:0px 0px 5px #444;
}
#introduction *{
	padding: 0;
}
#introduction *, #radio-input-container *{
	text-align: left;
}
#response-screen{
	text-align: left;
	font-weight:bold;
	color: white;
	background-color: black;
	border-radius: 3px;
}
.response-val{
	color: green;
}
label{
	cursor: pointer;
}
input{
	display:inline-block;
	cursor: pointer;
	padding:3%;
	font-size: 1.2em;
}
p{
	margin:0;
}
#input-file{
	display:none;
}
</style>
<script type="text/javascript">
	jQuery("#bt-choose-file").click(function(e){
		jQuery("#input-file").click();
	});
</script>