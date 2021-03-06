<?php
/**
 * Example showing how a dependant group (aka sub form) might be attached to a checkbox.
 */
function base()
{
    //Step 0: Declare dependencies
    global $DIC;
    $ui = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();
    $request = $DIC->http()->request();

    //Step 1: Define the dependent group (aka sub section)
    $dependant_field1 = $ui->input()->field()->text("Item 1", "Just some dependent group field");
    $dependant_field2 = $ui->input()->field()->text("Item 1", "Just another dependent group field");

    $dependant_group = $ui->input()->field()->dependantGroup([ "Sub Part 1" => $dependant_field1, "Sub Part 2" => $dependant_field2]);

    //Step 2: Define input and attach sub section
    $checkbox_input = $ui->input()->field()->checkbox("Checkbox", "Check to display dependant field.")
            ->withValue(true)
            ->withDependantGroup($dependant_group);

    //Step 3: Define form and form actions
    $DIC->ctrl()->setParameterByClass(
        'ilsystemstyledocumentationgui',
        'example_name',
        'checkbox'
    );

    $form_action = $DIC->ctrl()->getFormActionByClass('ilsystemstyledocumentationgui');
    $form = $ui->input()->container()->form()->standard($form_action, [ "checkbox" => $checkbox_input]);


    //Step 4: Implement some form data processing.
    if ($request->getMethod() == "POST"
        && $request->getQueryParams()['example_name'] == 'checkbox') {
        $form = $form->withRequest($request);
        $result = $form->getData();
    } else {
        $result = "No result yet.";
    }

    //Step 5: Render the checkbox with the enclosing form.
    return
        "<pre>" . print_r($result, true) . "</pre><br/>" .
        $renderer->render($form);
}
