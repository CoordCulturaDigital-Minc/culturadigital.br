<?php

class A_NextGen_Basic_Singlepic extends Mixin
{
    function initialize()
    {
        if ($this->object->name == NEXTGEN_BASIC_SINGLEPIC_MODULE_NAME)
        {
            $this->object->add_pre_hook(
				'validation',
				get_class(),
				'Hook_NextGen_Basic_Singlepic_Validation'
			);
        }
    }
}

class Hook_NextGen_Basic_Singlepic_Validation extends Hook
{
    function validation()
    {
    }
}
