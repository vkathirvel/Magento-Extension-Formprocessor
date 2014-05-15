<?php

/**
 * Check the what_a_mess field
 */
if ((int) $this->post['what_a_mess'] !== 7) {
    $this->form->setErrorMessage('The answer to the spam check question is wrong. Please try again.');
    throw new Exception();
}