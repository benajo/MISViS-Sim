$.ajaxSetup({ cache: false });

var staveHTML; // holds the template html for a new stave
var noteHTML; // holds the templace html for a new note

// the total number of staves that have been created so far
var staveCount;

// this function is called when the page finishes loading
$(function() {
	// add the name attribute to the Vextab textarea so its post data can be accessed
	$("#vextabContainer textarea").attr("name", "pieceData");

	// set the stave html template
	$.ajax({
		type: "GET",
		url: "./view/piece-edit-stave.php"
	})
	.done(function(html) {
		staveHTML = html;
	});

	// set the note html template
	$.ajax({
		type: "GET",
		url: "./view/piece-edit-note.php"
	})
	.done(function(html) {
		noteHTML = html;
	});

	// override the forms usual post to do an ajax call instead of refreshing the page
	$("#pieceEdit").submit(function() {
		var options = {
			url: "./controller/piece-edit-ajax.php",
			dataType: "json",
			success: function(json) {
				if (json.successMessage)
					$("#pieceEdit .displayMessages").html("<p class='success-message'>" + json.successMessage + "</p>");
				else if (json.errorMessage)
					$("#pieceEdit .displayMessages").html("<p class='error-message'>" + json.errorMessage + "</p>");
			}
		};

		// ajaxSubmit is from the jquery.form.min.js library
		$(this).ajaxSubmit(options);

		return false;
	});

	// add an event to all stave form fields to upadte the VexTab texarea when changed
	$("fieldset.stave input, fieldset.stave select").each(function() {
		$(this)
			.keyup(function() { updateVexTabTextarea(); })
			.change(function() { updateVexTabTextarea(); });
	});

	// set the current amount of staves
	staveCount = $("#staveList fieldset.stave").length;
});

/**
 * Adds a new stave to the page and adds 4 notes to that stave.
 */
function pieceNewStave()
{
	// increment the stave count by 1
	staveCount += 1;

	// replace all of the _STAVE_NO_ placeholders with the new stave count
	var html = staveHTML.replace(/_STAVE_NO_/gi, staveCount);

	// add the stave to the page
	$("#staveList").append(html);

	// add an event to the form fields to upadte the VexTab textarea when changed
	$("#pieceStave" + staveCount + " select, #pieceStave" + staveCount + " input").each(function() {
		$(this)
			.change(function() { updateVexTabTextarea(); })
			.keyup(function() { updateVexTabTextarea(); });
	});

	// add a note to this new stave
	// there is potential to add more via the loop
	for (var i = 0; i < 1; i++) {
		pieceNewNote(staveCount);
	}

	// set the first note duration be a quarter
	$($("#pieceStave" + staveCount + " fieldset.noteEntry .duration option[data-vex=':w']")[0]).attr("selected", "selected");
	// set the first note octave be 4
	$($("#pieceStave" + staveCount + " fieldset.noteEntry .octave option[value='4']")[0]).attr("selected", "selected");

	// update the stave title numbers
	updateStaveNos();

	// update the VexTab textarea
	updateVexTabTextarea();
}

/**
 * Adds a new note to the specified stave.
 */
function pieceNewNote(staveNo)
{
	// count how many notes already exist in the stave and add 1
	var len = $("#pieceStave" + staveNo + " fieldset.noteEntry").length + 1;

	// replace all _STAVE_NO_ palceholders with the stave number
	// and _NOTE_NO_ with the new notes length
	var html = noteHTML.replace(/_STAVE_NO_/gi, staveNo).replace(/_NOTE_NO_/gi, len);

	// add the note to the page
	$("#pieceStave" + staveNo + " .notes").append(html);

	// add an event to the form fields to upadte the VexTab textarea when changed
	$("#pieceStaveNote" + len + " select, #pieceStaveNote" + len + " input").each(function() {
		$(this).change(function() { updateVexTabTextarea(); });
		$(this).keyup(function() { updateVexTabTextarea(); });
	});

	// updaet the VexTab textarea
	updateVexTabTextarea();
}

/**
 * Deletes the specified note.
 */
function pieceDeleteNote(staveNo, noteNo)
{
	// cannot delete the last note
	if ($("#pieceStave" + staveNo + " .noteEntry").length > 1) {
		// deletes the note element
		$("#pieceStave" + staveNo + " #pieceStaveNote" + noteNo).remove();

		updateVexTabTextarea();
	}
}

/**
 * Deletes the specified stave and all of its notes.
 */
function pieceDeleteStave(staveNo)
{
	// cannot delete the final stave
	if ($("fieldset.stave").length > 1) {
		// deletes the stave element
		$("#pieceStave" + staveNo).remove();

		updateStaveNos();

		updateVexTabTextarea();
	}
}

/**
 * Each stave on the page has a title "Stave 1", "Stave 2", etc.
 * This function updates those numbers to always be sequential.
 *
 * This is important if Stave 2 was deleted, so that if there was a stave 3
 * it can now become stave 2, and so on.
 */
function updateStaveNos()
{
	var i = 1;

	// loop over each stave
	$("#staveList fieldset.stave").each(function() {
		// update the stave number for the stave
		$(this).find(".staveNo").html(i);

		i++;
	});
}

/**
 * Updates the Vextab textarea by looping through all of the staves and their notes.
 * The VexTab textarea is essential for the VexTab library to render the music on the page.
 */
function updateVexTabTextarea()
{
	// the string for the entire textarea content
	var s = "";
	// the stave form elements
	var clef, key, topSpace, bottomSpace, topTime, bottomTime;
	// the note form elements
	var noteElement, note, duration, octave, dotted;

	// loop over each stave
	$("fieldset.stave").each(function() {
		// assigns all of the stave form field values
		clef        = $(this).find(".clef option:selected").data("vex");
		key         = $(this).find(".key option:selected").data("vex");
		topSpace    = $(this).find(".topSpace").val();
		bottomSpace = $(this).find(".bottomSpace").val();
		topTime     = $(this).find(".topTime").val();
		bottomTime  = $(this).find(".bottomTime").val();

		// sets the space at the top of the stave
		s += "options space=" + topSpace + "\n";
		// required for all staves
		s += "tabstave tablature=false notation=true\n";
		// sets the key, time and clef values
		s += "key=" + key + "\n";
		s += "time=" + topTime + "/" + bottomTime + "\n";
		s += "clef=" + clef + "\n";

		// beings the notes section
		s += "notes ";

		// loop over each note
		$(this).find(".noteEntry").each(function() {
			noteElement = $(this).find(".note option:selected");

			note     = noteElement.data("vex");
			duration = $(this).find(".duration option:selected").val().length ? $(this).find(".duration option:selected").data("vex") : duration;
			octave   = $(this).find(".octave option:selected").val().length ? $(this).find(".octave option:selected").val() : octave;
			dotted   = $(this).find(".dotted:checked").length ? "d" : "";

			// set the note values
			s += duration;
			s += dotted + " ";
			s += note;
			// if the note is a non-note, such as a bar line, do not add the octave
			s += (noteElement.data("non-note") == "0" ? "/" + octave : "") + " ";
		});

		// sets the space below the stave
		s += "\noptions space=" + bottomSpace + "\n";

	});

	// update the textarea
	$("#vextabContainer textarea")
		.val(s)   // set the new textarea content
		.keyup(); // keyup simulates normal use of the VexTab textarea to re-render the music
}
