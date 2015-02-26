var c; // VexTab canvas element
var data; // for pixel colours

var scale; // scale value of the VexTab canvas
var x; // x coordinate of start of lines
var staveLineHeight; // height of stave lines in pixels
var staveSpaceHeight; // height of stave space between lines in pixels
var staveLines; // array of all stave line positions

var overlayDivs; // div elements inside the overlay

$(function() {
	c = document.getElementsByClassName("vex-canvas")[0]; // set the canvas element
	// scale = parseFloat($(".editor").val().match(/scale=([0-9]+(\.[0-9]+)?)/i)[1]);
	scale = parseFloat($("#vextabContainer").attr("scale")); // set the scale value
	x = 12*scale; // set the x position
});

/**
 * This method assumes the dimensions of the canvas layout through personal inspection.
 * This method was by far the fastest and was chosen for implementation.
 * Values:
 *    - height of the lines in the stave = 2px
 *    - height of the space between the lines on the stave = 8px
 *    - space at the top of the canvas = 38px
 *    - space from the top of 1 stave to the top of the next = 90px
 *    - manual spacings entered are in pixels and therefore easy to account for when
 *      calculating positions of stave lines.
 */
function calculateLinePositions()
{
	staveLines = [];

	var staveNo = $(".editor").val().match(/tabstave/ig).length; // amount of staves in the canvas

	staveLineHeight = 2*scale, staveSpaceHeight = 8*scale;

	var spacings = $(".editor").val().match(/space=([0-9]+)/ig); // the individual spacings in the canvas
	// remove the text and just leave the integer value
	for (var i = 0; i < spacings.length; i++) {
		spacings[i] = parseFloat(spacings[i].split("=")[1]);
	};

	// defaults
	var topSpace = (38+1)*scale; // +1 because 39 is where the first line begins
	var topToTopSpace = 90*scale;

	// each new stave has to account for previous spacings
	// so tally up the total space as the loop progresses
	var cumulativeSpace = 0;

	// loop over each stave to find the stave line positions
	for (var i = 0; i < staveNo; i++) {
		// stave i has to account for space i*2 and i*2-1
		// when i = 0, then i*2-1 is out of bounds, the if below accounts for it.
		// a full explanation of this spacing calculation will be in the report
		if (i == 0) {
			cumulativeSpace += (spacings[i*2])*scale;
		}
		else {
			cumulativeSpace += (spacings[i*2] + spacings[i*2-1])*scale;
		}

		// 5 lines per stave
		for (var j = 0; j < 5; j++) {
			staveLines.push((topSpace) + (topToTopSpace*i) + ((staveLineHeight+staveSpaceHeight)*j) + cumulativeSpace);
		};
	};
}

/**
 * This function creates the div elements that overlay the canvas element.
 * 1. One div with the same dimesions as the canvas.
 * 2. A div for each stave space.
 * 3. A div for each pixel within the stave spaces.
 */
function createOverlays()
{
	$("#overlay").remove();

	// create the overlay element
	var overlay = document.createElement("div");
	overlay.id = "overlay";
	// ensure it has the same dimensions as the canvas
	overlay.style.width  = c.width + "px";
	overlay.style.height = c.height + "px";
	// append it to the vextab container
	document.getElementById("vextabContainer").appendChild(overlay);

	var width = c.width - x*2; // width of inner overlay elements
	var height = staveSpaceHeight; // height of inner overlay elements
	var r; // variable for random integer
	var div;

	// create the inner elements
	for (var i = 0; i < staveLines.length; i++) {
		// don't draw below 5th line
		if ((i+1) % 5 > 0) {
			div = document.createElement("div");
			div.style.width      = width + "px";
			div.style.height     = height-2 + "px"; // -2 to account for padding
			div.style.top        = staveLines[i] + staveLineHeight + "px";
			div.style.left       = x + "px";
			div.style.paddingTop = 2 + "px";

			document.getElementById("overlay").appendChild(div);
		}
	};

	overlayDivs = document.getElementById("overlay").children;

	for (var i = 0; i < overlayDivs.length; i++) {
		for (var j = 0; j < width; j++) {
			for (var k = 2; k <= (height-2); k++) {
				r = Math.random() * 2;

				div = document.createElement("div");
				div.style.backgroundColor = r < 1 ? 'rgb(100,100,100)' : ''; // 50% chance of being grey
				div.style.width = "1px";
				div.style.height = "1px";

				overlayDivs[i].appendChild(div);
			}
		}
	};
}

function flicker()
{
	for (var i = 0; i < overlayDivs.length; i++) {
		eval(
			'var Fun'+i+' = function() {'+
				'console.log("Fun'+i+'");'+
				'if (overlayDivs['+i+'].style.visibility != "visible") {'+
					'overlayDivs['+i+'].style.visibility = "visible";'+
					't'+i+' = setTimeout(Fun'+i+', parseInt(Math.random()*(2000-1000)+1000));'+
				'}'+
				'else {'+
					'overlayDivs['+i+'].style.visibility = "hidden";'+
					't'+i+' = setTimeout(Fun'+i+', parseInt(Math.random()*(10-5)+5));'+
				'}'+

			'}; Fun'+i+'()'
		);
	}
}

function fizzing()
{
	calculateLinePositions();
	createOverlays();
	flicker();
}
function fizzingOff()
{
	$("#overlay").remove();

	for (var i = 0; i < overlayDivs.length; i++) {
		eval('clearTimeout(t'+i+')');
	}
}


// --------


$.ajaxSetup({ cache: false });

var staveHTML, noteHTML;

$(function() {
	//
	$.ajax({
		type: "GET",
		url: "./view/piece-edit-stave.php"
	})
	.done(function(html) {
		staveHTML = html;
	});

	//
	$.ajax({
		type: "GET",
		url: "./view/piece-edit-note.php"
	})
	.done(function(html) {
		noteHTML = html;
	});

	//
	$("#pieceEdit").submit(function() {
		var options = {
			url: "./controller/piece-edit-ajax.php",
			dataType: "json",
			success: function(json) {
				if (json.successMessage)
					$("#pieceEdit .displayMessages").html("<div class='success-message'>" + json.successMessage + "</div>");
				else if (json.errorMessage)
					$("#pieceEdit .displayMessages").html("<div class='error-message'>" + json.errorMessage + "</div>");
			}
		};

		$(this).ajaxSubmit(options);

		return false;
	});

	//
	$("fieldset.stave input").each(function() {
		$(this).keyup(function() { updateVexTabTextarea(); });
	});
	$("fieldset.stave select, fieldset.stave input[type=checkbox]").each(function() {
		$(this).change(function() { updateVexTabTextarea(); });
	});
});

function pieceNewStave()
{
	var len = $("#staveList fieldset.stave").length + 1;

	var html = staveHTML.replace(/_STAVE_NO_/gi, len);

	$("#staveList").append(html);

	$("#pieceStave" + len + " select").each(function() {
		$(this).change(function() { updateVexTabTextarea(); });
	});

	$("#pieceStave" + len + " input").each(function() {
		$(this).keyup(function() { updateVexTabTextarea(); });
	});

	for (var i = 0; i < 4; i++) {
		pieceNewNote(len);
	}

	$($("#pieceStave" + len + " fieldset.noteEntry .duration option[data-vex=':q']")[0]).attr("selected", "selected");
	$($("#pieceStave" + len + " fieldset.noteEntry .octave option[value='4']")[0]).attr("selected", "selected");

	updateVexTabTextarea();
}

function pieceNewNote(staveNo)
{
	var len = $("#pieceStave" + staveNo + " fieldset.noteEntry").length + 1;

	var html = noteHTML.replace(/_STAVE_NO_/gi, staveNo-1).replace(/_NOTE_NO_/gi, len);

	$("#pieceStave" + staveNo + " .notes").append(html);

	$("#pieceStaveNote" + len + " select, #pieceStaveNote" + len + " input[type=checkbox]").each(function() {
		$(this).change(function() { updateVexTabTextarea(); });
	});

	$("#pieceStaveNote" + len + " input").each(function() {
		$(this).keyup(function() { updateVexTabTextarea(); });
	});

	updateVexTabTextarea();
}

function pieceDeleteNote(staveNo, noteNo)
{
 $("#pieceStave" + staveNo + " #pieceStaveNote" + noteNo).remove();

 updateVexTabTextarea();
}

function updateVexTabTextarea()
{
	var s = "";
	var clef, key, topSpace, bottomSpace, topTime, bottomTime;
	var note, duration, octave, dotted;

	var noteField;

	$("fieldset.stave").each(function() {
		clef        = $(this).find(".clef option:selected").data("vex");
		key         = $(this).find(".key option:selected").data("vex");
		topSpace    = $(this).find(".topSpace").val();
		bottomSpace = $(this).find(".bottomSpace").val();
		topTime     = $(this).find(".topTime").val();
		bottomTime  = $(this).find(".bottomTime").val();

		s += "options space=" + topSpace + "\n";
		s += "tabstave tablature=false notation=true\n";
		s += "key=" + key + "\n";
		s += "time=" + topTime + "/" + bottomTime + "\n";
		s += "clef=" + clef + "\n";

		s += "notes ";

		$(this).find(".noteEntry").each(function() {
			noteField = $(this).find(".note option:selected");

			note     = noteField.data("vex");
			duration = $(this).find(".duration option:selected").val().length ? $(this).find(".duration option:selected").data("vex") : duration;
			octave   = $(this).find(".octave option:selected").val().length ? $(this).find(".octave option:selected").val() : octave;
			dotted   = $(this).find(".dotted:checked").length ? "d" : "";

			s += duration + dotted + " " + note + (noteField.data("non-note") == "0" ? "/" + octave : "") + " ";
		});

		s += "\noptions space=" + bottomSpace + "\n";

	});

	$("#vextabContainer textarea").val(s).keyup();
}
