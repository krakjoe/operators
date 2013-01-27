dnl $Id$
dnl config.m4 for extension operators

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary. This file will not work
dnl without editing.

dnl If your extension references something external, use with:

dnl PHP_ARG_WITH(operators, for operators support,
dnl Make sure that the comment is aligned:
dnl [  --with-operators             Include operators support])

dnl Otherwise use enable:

dnl PHP_ARG_ENABLE(operators, whether to enable operators support,
dnl Make sure that the comment is aligned:
dnl [  --enable-operators           Enable operators support])

if test "$PHP_OPERATORS" != "no"; then
  dnl Write more examples of tests here...

  dnl # --with-operators -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/operators.h"  # you most likely want to change this
  dnl if test -r $PHP_OPERATORS/$SEARCH_FOR; then # path given as parameter
  dnl   OPERATORS_DIR=$PHP_OPERATORS
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for operators files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       OPERATORS_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$OPERATORS_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the operators distribution])
  dnl fi

  dnl # --with-operators -> add include path
  dnl PHP_ADD_INCLUDE($OPERATORS_DIR/include)

  dnl # --with-operators -> check for lib and symbol presence
  dnl LIBNAME=operators # you may want to change this
  dnl LIBSYMBOL=operators # you most likely want to change this 

  dnl PHP_CHECK_LIBRARY($LIBNAME,$LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $OPERATORS_DIR/lib, OPERATORS_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_OPERATORSLIB,1,[ ])
  dnl ],[
  dnl   AC_MSG_ERROR([wrong operators lib version or lib not found])
  dnl ],[
  dnl   -L$OPERATORS_DIR/lib -lm
  dnl ])
  dnl
  dnl PHP_SUBST(OPERATORS_SHARED_LIBADD)

  PHP_NEW_EXTENSION(operators, operators.c, $ext_shared)
fi
