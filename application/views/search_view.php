<? $this->load->helper("linkeddata_helper"); ?>
<? $this->load->helper("impact_helper"); ?>
<!DOCTYPE html>
<?=$metaDisplay;?>
<html>
<head>
	<title>Footprinted.org Search</title>
	
	<?=$styles;?>
	<?=$headerDisplay;?>
	<?=$scripts;?>


</head>
<body id="home">
	<div id ="contentwrap">	
	<?= $navigationDisplay;?>
	
	<div id="columnwide">
		<form action="/search/" method="post">
			<div id="search">
			<div>
				<label>Keyword</label> 
				<input type="text" name="keyword" tabindex="5" />
			</div>
			<div>
				<label>Year Interval</label> 
				<input type="text" name="startYear" class="tiny" tabindex="5" /> 
				<input type="text" name="endYear" class="tiny" tabindex="5" />
			</div>
			<div><label>Country</label> <input type="text" name="country" tabindex="5" /> </div>
			<div><label>Category</label> 
			<input type="hidden" name="category" id="category" tabindex="5" /> 
			<?
				if (isset($menu) == true) {
					foreach ($menu as $menu_item) {
						echo '<a id="'.$menu_item['uri'].'" class="cat">'.$menu_item['label'].'</a> / ';
					}
				}
			?>
			<input type="submit" value="Search &rsaquo; &rsaquo; " tabindex="6" /></div>
			</div>
		</form>

				<?	
					echo "<h1 class='about'>";
					if (isset($search_terms['$keyword']) == true) {
						echo "<em>".$search_terms['keyword']."</em> ";
					} else {
						echo "Something ";
					}
					if (isset($search_terms['category']) == true) {
						echo "that belongs to category <em>" . $search_terms['category']. "</em> ";
					}
					if (isset($search_terms['startYear']) == true || isset($search_terms['endYear']) == true) {
						echo "during ";
						if (isset($search_terms['startYear']) == true) {
							echo "<em>" . $search_terms['startYear'] . "</em> ";
						}
						if (isset($search_terms['startYear']) == true || isset($search_terms['endYear']) == true) {
							echo "- ";
						}
						if (isset($search_terms['endYear']) == true) {
							echo "<em>" . $search_terms['endYear'] . "</em> ";
						}
					}
					if (isset($search_terms['country']) == true) {
						echo "in <em>" . $search_terms['country']. "</em> ";
					}
					if (isset($search_terms['offset']) == false) {
						$search_terms['offset'] = 0;
					}
					if (isset($set) == true) {
							echo ': '.$set_count. ' footprints found. (Showing '.($search_terms['offset']+1).'-'.($search_terms['offset']+count($set)).')';
					}
					echo "</h1>";
					echo "<div>";
					$search_string = "";
					if (isset($search_terms) == true) {
						foreach ($search_terms as $term=>$value) {
							if ($term != "offset") {
								$search_string .= "&".$term."=".$value;
							}
						}
					}
					if ($search_terms['offset'] != 0) {	
						echo '<a href="/search?offset='.(int)($search_terms['offset']-100).$search_string.'">Previous</a>';
					}
					if ($search_terms['offset'] != 0 && ($search_terms['offset']+count($set)) < $set_count) {
						echo " | ";
					}
					if (($search_terms['offset']+count($set)) < $set_count) {
						echo '<a href="/search?offset='.(int)($search_terms['offset']+100).$search_string.'">Next</a>';
					}
					echo "</div>";
					
				?>
				<?	
					if (isset($set) == true) {
						if (count($set) > 0) {
						foreach ($set as $row) {
							$impacts = "";
							if($row->co2e){$impacts .= "CO2e | ";}
							if($row->water){$impacts .= " Water | ";}
							if($row->waste){$impacts .= " Waste | ";}
							if($row->energy){$impacts .= " Energy | ";}
							if ($impacts != "") {
								$impacts = substr($impacts, 0, -2);
							}
							if($row->year == 0){$year="-";}else{$year=$row->year;}
							if($row->country == ""){$geo="-";}else{$geo=$row->country;}
							echo '<a href="/'.$row->uri .'"><div class="medium grey square" id="'.$row->uri.'">
							<div class="squareplace"><p>'.$geo.'</p></div>
							<div class="squareyear"><p>'.$year.'</p></div>
							<div class="squaretext"><p>'.$row->name.'</p></div>
							<div class="squareimpacts"><p>'.$impacts.'</p></div>
							</div></a>';
						}
					} else {
						echo "No results found.";
					}
					}
				?>
	</div>
	<?=$footerDisplay;?>
</div>
	
	<script>
	
	$(function() {
		$( ".blue" ).click(function(){
			if (typeof opensquare!="undefined"){
				opensquare.load('/lca/getName/'+opensquare.attr('id'));
				opensquare.removeClass("medium");
				opensquare.removeClass("grey");
                opensquare.addClass("small");
				opensquare.addClass("blue");
			}
			opensquare = $(this);
            $(this).removeClass("small");
			$(this).removeClass("blue");
            $(this).addClass("medium");
			$(this).addClass("grey");
			$('#columnleft').masonry({	  
				  itemSelector: '.square', columnWidth:10, });
			$(this).load('/lca/getImpacts/'+$(this).attr('id'));	
		});
		
	});	

	$(function() {
	    //Get Divs
	    //$('#leftcolumn > [square]').each(function(i) {
			// Get CO2
	//		co2 = ('/lca/getCO2/'+opensquare.attr('id'));
			// Scale
	//		side = Math.sqrt(co2*2500);
	//		$(this).width(side);
	//		$(this).height(side);
	//    });
	// Do Masonry
//	$('#columnleft').masonry({	  
		  itemSelector: '.square', columnWidth:10, });
//	});
	
	$( ".aaa" ).click(function(){
			thisone = $(this);
			$.getJSON('/lca/getCO2/'+$(this).attr('id'), function(data) {;
				side = Math.sqrt(data.CO2*2500);
				side = Math.round(side);
				if(side > 400){ side = 400; }
				thisone.width(side);
				thisone.height(side);

	// Do Masonry
	$('#columnleft').masonry({	  
		  itemSelector: '.square', columnWidth:10, });
		});
	});	});
	</script>
</body>
</html>