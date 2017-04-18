<link rel="stylesheet" href="/hooks/lib/chosen/chosen.css">
<script type="text/javascript" src="/hooks/lib/chosen/chosen.jquery.js"></script>
<script language="javascript">

    $(document).ready(function() {

        createDropDownOptions();

        for (i = 1; i <= 20; i++) {
            if(userFieldDisplayed(i)){
                addChosenDropDown(i);
            }
        }

    });

    var ldap_users = <?php echo $this->getLdapUsers(); ?>;
    var dropdown_options = [];

    function addChosenDropDown(index){
        createDropDownMenu(index);
        monitorRadioButtons(index);
    }

    $.fn.exists = function () {
        return this.length !== 0;
    };

    function createDropDownOptions(){
        $.each(ldap_users, function(i, item) {
            dropdown_options.push('<option value="'+ i +'">'+ item["first_name"] + ' ' + item["last_name"] +'</option>');
        });
    }

    function createDropDownMenu(index){
        selector = getInputSelector(index);
        current_value = selector.val();

        selector.wrap("<div id='" + getContainerID(index) + "'></div>");
        selector.remove();
        $('div#' + getContainerID(index)).append('<select name="' + getInputName(index) + '"></select>');

        var dropdown = $('select[name=' + getInputName(index) + ']');

        addDropDownItems(dropdown);
        selectCurrentDropDownValue(dropdown, current_value);
        addChosen(dropdown);
    }


    function selectCurrentDropDownValue(dropdown, current_value){
        dropdown.val(current_value);
    }

    function addDropDownItems(dropdown){
        dropdown.html(dropdown_options.join(' '));
    }

    function monitorRadioButtons(index){
        var radio_selector = $('input[name=add_new_' + getInputName(index) + '___radio]');

        radio_selector.change(function() {
            var next_index = index + 1;
            if(this.value == 1 && dropDownDoesNotExist(next_index)) setTimeout(addChosenDropDown, 50, (next_index));
        });
    }

    function getInputSelector(index){
        return $('input[name=' + getInputName(index) + ']');
    }

    function getContainerID(index){
        return getInputName(index) + '_container';
    }

    function getInputName(index){
        return 'user_' + index;
    }

    function addChosen(dropdown){
        dropdown.chosen("destroy");
        dropdown.chosen({include_empty: true,
                         placeholder_text_single: "Click here and type user's name.",
                         no_results_text: "User not found."});
    }

    function userFieldDisplayed(index){
        return getInputSelector(index).is(":visible");
    }

    function dropDownDoesNotExist(index){
        return !$('select[name=' + 'user_' + (index) + ']').exists();
    }

</script>