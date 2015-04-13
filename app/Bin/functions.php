<?php 

/** Global functions */
function d( $arg )
{
		var_dump( $arg );
}

function dr( $arg )
{
	echo "<pre>";
    var_dump( $arg );
    echo "</pre>";
}

function de( $arg )
{
	var_dump( $arg ); exit;
}

function der( $arg )
{
    echo "<pre>"; 
    var_dump( $arg ); exit;
    echo "</pre>";
}

function td()
{
	$args = func_get_args();

	echo "\n<pre style=\"border:1px solid #ccc;padding:10px;" .
         "margin:10px;font:14px courier;background:whitesmoke;" .
	     "display:block;border-radius:4px;\">\n";

	$trace = debug_backtrace(false);
	$offset = (@$trace[2]['function'] === 'dd') ? 2 : 0;

	echo "<span style=\"color:red\">" .
	       @$trace[1+$offset]['class'] . "</span>:" .
	       "<span style=\"color:blue;\">" .
	       @$trace[1+$offset]['function'] . "</span>:" .
	       @$trace[0+$offset]['line'] . " " .
	       "<span style=\"color:green;\">" .
	       @$trace[0+$offset]['file'] . "</span>\n";

	if ( ! empty($args)) {
		call_user_func_array('var_dump', $args);
	}

	echo "</pre>\n";
}

function dd()
{
	call_user_func_array('dump', func_get_args());
	die();
}

function array_get($array, $key, $default = null)    
{
         if (is_null($key)) return $array;
         
         if (isset($array[$key])) return $array[$key];
 
         foreach (explode('.', $key) as $segment)
         {
             if ( ! is_array($array) or ! array_key_exists($segment, $array))
             {
                 return value($default);
             }
 
             $array = $array[$segment];
         }
 
         return $array;
     }

function array_set(&$array, $key, $value)
{
    if (is_null($key)) return $array = $value;
    $keys = explode('.', $key);
    while (count($keys) > 1)
    {
        $key = array_shift($keys);
        // If the key doesn't exist at this depth, we will just create an empty array
        // to hold the next value, allowing us to create the arrays to hold final
        // values at the correct depth. Then we'll keep digging into the array.
        if ( ! isset($array[$key]) or ! is_array($array[$key]))
        {
            $array[$key] = array();
        }
        $array =& $array[$key];
    }
    $array[array_shift($keys)] = $value;
}