PHP_ARG_ENABLE(operators, whether to enable Operators,
[ --enable-operators Enable Operators])
if test "$PHP_OPERATORS" != "no"; then
	PHP_NEW_EXTENSION(operators, operators.c, $ext_shared)
	PHP_ADD_INCLUDE($ext_builddir)
	PHP_SUBST(OPERATORS_SHARED_LIBADD)
	PHP_ADD_MAKEFILE_FRAGMENT
fi
