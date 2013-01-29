/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) Joe Watkins 2013                                		 |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author: Joe Watkins <joe.watkins@live.co.uk>                         |
  +----------------------------------------------------------------------+
*/

/* $Id$ */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_operators.h"

zend_class_entry *operators_class_entry;

/* {{{ operators_functions[] */
const zend_function_entry operators_functions[] = {PHP_FE_END};
/* }}} */

/* {{{ operators_arginfo */
ZEND_BEGIN_ARG_INFO_EX(operators_arginfo, 0, 0, 1)
	ZEND_ARG_INFO(0, opcode)
	ZEND_ARG_INFO(0, zval)
ZEND_END_ARG_INFO()
/* }}} */

/* {{{ operators_methods[] */
const zend_function_entry operators_methods[] = {
	PHP_ABSTRACT_ME(Operators, __operators, operators_arginfo)
	PHP_FE_END
};
/* }}} */

/* {{{ operators_module_entry
 */
zend_module_entry operators_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
	STANDARD_MODULE_HEADER,
#endif
	"operators",
	operators_functions,
	PHP_MINIT(operators),
	PHP_MSHUTDOWN(operators),
	NULL,
	NULL,
	PHP_MINFO(operators),
#if ZEND_MODULE_API_NO >= 20010901
	"0.1",
#endif
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_OPERATORS
ZEND_GET_MODULE(operators)
#endif

static int opcodes[] = {
	ZEND_ADD,
	ZEND_SUB,
	ZEND_MUL,
	ZEND_DIV,
	ZEND_MOD,
	ZEND_SL,
	ZEND_SR,
	ZEND_BW_OR,
	ZEND_BW_AND,
	ZEND_BW_XOR,
	
	ZEND_IS_IDENTICAL,
	ZEND_IS_NOT_IDENTICAL,
	ZEND_IS_EQUAL,
	ZEND_IS_NOT_EQUAL,
	
	ZEND_ASSIGN_ADD,
	ZEND_ASSIGN_SUB,
	ZEND_ASSIGN_MUL,
	ZEND_ASSIGN_DIV,
	ZEND_ASSIGN_MOD,
	ZEND_ASSIGN_SL,
	ZEND_ASSIGN_SR,
	ZEND_ASSIGN_BW_OR,
	ZEND_ASSIGN_BW_AND,
	ZEND_ASSIGN_BW_XOR,

	ZEND_PRE_INC,
	ZEND_PRE_DEC,
		
	ZEND_POST_INC,
	ZEND_POST_DEC,
	0
};

static const char* opconsts[] = {
	"OPS_ADD",
	"OPS_SUB",
	"OPS_MUL",
	"OPS_DIV",
	"OPS_MOD",
	"OPS_SL",
	"OPS_SR",
	"OPS_BW_OR",
	"OPS_BW_AND",
	"OPS_BW_XOR",	

	"OPS_IDENTICAL",
	"OPS_NOT_IDENTICAL",
	"OPS_IS_EQUAL",
	"OPS_IS_NOT_EQUAL",

	"OPS_ASSIGN_ADD",
	"OPS_ASSIGN_SUB",
	"OPS_ASSIGN_MUL",
	"OPS_ASSIGN_DIV",
	"OPS_ASSIGN_MOD",
	"OPS_ASSIGN_SL",
	"OPS_ASSIGN_SR",
	"OPS_ASSIGN_BW_OR",
	"OPS_ASSIGN_BW_AND",
	"OPS_ASSIGN_BW_XOR",

	"OPS_PRE_INC",
	"OPS_PRE_DEC",
	
	"OPS_POST_INC",
	"OPS_POST_DEC",

	NULL
};
#if PHP_VERSION_ID < 50499
	#define EX_TMP_VAR(execute_data, offset) ((temp_variable *)((char*)execute_data->Ts + offset))
	#define EX_CV_NUM(execute_data, offset) (&execute_data->CVs[offset])
#endif
#define OPS_ME "__operators"
#define OPS_LEFT 1
#define OPS_RIGHT 2
#define OPS_DIR_INT ((Z_TYPE_P(lhs) == IS_OBJECT) ? OPS_LEFT : OPS_RIGHT)
#define OPS_DIR_VAL ((OPS_DIR_INT == OPS_LEFT) ? lhs : rhs)
#define OPS_DIR_DATA ((Z_TYPE_P(lhs) == IS_OBJECT) ? rhs : lhs)
#define OPS_FOP_FREE(i) {if (fops[i].var) zval_dtor(fops[i].var);}
#define OPS_FOPS_FREE() {OPS_FOP_FREE(0); OPS_FOP_FREE(1);}
#define OPS_ASSIGN(opcode) (opcode <= 20 && opcode >= 1) ? 0 : 1

static inline zval* operators_get_ptr(znode_op *from, int type, zend_free_op *freeing, zend_execute_data *execute_data TSRMLS_DC) {
	freeing->var = NULL;
		
	switch(type) {
		case IS_CONST:    {
			return from->zv;
		}
		case IS_VAR:      {
			return EX_TMP_VAR(execute_data, from->var)->var.ptr; 
		}
		case IS_TMP_VAR:  return (freeing->var = &EX_TMP_VAR(execute_data, from->var)->tmp_var);
		case IS_CV:       {
			zval ***ret = EX_CV_NUM(execute_data, from->var);

		    if (!*ret) {
		        zend_compiled_variable *cv = &EG(active_op_array)->vars[from->var];

		        if (zend_hash_quick_find(EG(active_symbol_table), cv->name, cv->name_len+1, cv->hash_value, (void**)ret)==FAILURE) {
		            return &EG(uninitialized_zval);
		        }
		    }
			
            return **ret;
		}
	}
	
	return NULL;
}

static inline void operators_set_result(zval *result, zend_op *opline, zend_execute_data *execute_data TSRMLS_DC)
{
	switch (opline->result_type) {
		case IS_TMP_VAR:
			EX_TMP_VAR(execute_data, opline->result.var)->tmp_var = *result;
			if (Z_TYPE_P(result) == IS_OBJECT) {
				Z_OBJ_HT_P(result)->add_ref(
					result TSRMLS_CC
				);
			}
			return;

		case IS_VAR:
			EX_TMP_VAR(execute_data, opline->result.var)->var.ptr = result;
			EX_TMP_VAR(execute_data, opline->result.var)->var.ptr_ptr = &EX_TMP_VAR(execute_data, opline->result.var)->var.ptr;
			return;
	}
}

static inline int operators_opcode_handler(ZEND_OPCODE_HANDLER_ARGS) {
	zend_op *line = NULL;
	zend_uint handled = 0;
	zend_uint sided = 0;
	zend_free_op fops[2] = {{NULL}, {NULL}};

	{
		line = (execute_data->opline);
		
		if (line) {
			zval *lhs = NULL, *rhs = NULL;
			
			lhs = operators_get_ptr(&line->op1, line->op1_type, &fops[0], execute_data TSRMLS_CC);
			
			switch(line->opcode) {
				/* these work on objects */
				case ZEND_PRE_INC:
				case ZEND_PRE_DEC:
				case ZEND_POST_INC:
				case ZEND_POST_DEC:
					/* nothing to see here */
				break;

				default: {
					rhs = operators_get_ptr(
						&line->op2, line->op2_type, &fops[1], execute_data TSRMLS_CC
					);
					sided = 1;
				}
			}
			
			if ((!sided && lhs) || ((lhs != NULL) && (rhs != NULL))) {
				if ((Z_TYPE_P(lhs) == IS_OBJECT) || (sided && Z_TYPE_P(rhs) == IS_OBJECT)) {
					zend_function *zcall;
					zend_uint side = OPS_DIR_INT;
					
					if (zend_hash_find(
						(side == OPS_LEFT) ? &Z_OBJCE_P(lhs)->function_table : &Z_OBJCE_P(rhs)->function_table,
						OPS_ME, sizeof(OPS_ME),
						(void **) &zcall
					) == SUCCESS) {
						zval *zopcode, *zresult, *zparams;

						zend_fcall_info info;
						zend_fcall_info_cache cache;	

						MAKE_STD_ZVAL(zopcode);
						
						ZVAL_LONG(zopcode, line->opcode);
						
						{
							info.size = sizeof(info);
							info.object_ptr = OPS_DIR_VAL;
							info.function_name = NULL;
							info.retval_ptr_ptr = &zresult;
							info.no_separation = 1;
							info.symbol_table = NULL;							
							info.param_count = 0;
							info.params = NULL;
							
							ALLOC_INIT_ZVAL(zparams);

							array_init(zparams);							
							{
								add_next_index_zval(zparams, zopcode);
								
								if (sided) {
									add_next_index_zval(zparams, OPS_DIR_DATA);

									Z_ADDREF_P(OPS_DIR_DATA);
								}

								zend_fcall_info_args(&info, zparams TSRMLS_CC);
							}

							cache.initialized = 1;
							cache.function_handler = zcall;
							cache.calling_scope = EG(scope);
							cache.called_scope = Z_OBJCE_P(OPS_DIR_VAL);
							cache.object_ptr = OPS_DIR_VAL;

							zend_call_function(&info, &cache TSRMLS_CC);
							
							if (zresult) {
								if (zresult != EG(uninitialized_zval_ptr)) {
									handled = 1;
									operators_set_result(
										zresult, line, execute_data TSRMLS_CC
									);
									FREE_ZVAL(zresult);
								}
							}

							zval_ptr_dtor(&zparams);

							efree(info.params);
						}
					}
				}
			}
		}
	}

	if (handled) {
		OPS_FOPS_FREE();

		execute_data->opline++;		

		return ZEND_USER_OPCODE_CONTINUE;
	}	

	return ZEND_USER_OPCODE_DISPATCH;
}
	
/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(operators)
{	
	zend_class_entry oe;
	int *opcode = opcodes;
	const char* const* opconst = opconsts;
	
	INIT_CLASS_ENTRY(
		oe, "Operators", operators_methods
	);
	operators_class_entry=zend_register_internal_class(&oe TSRMLS_CC);
	operators_class_entry->ce_flags |= ZEND_ACC_INTERFACE;

	while ((*opcode)) {
		zend_register_long_constant(
			(*opconst), strlen((*opconst))+1, (*opcode), 
			CONST_CS | CONST_PERSISTENT, module_number TSRMLS_CC
		);
		zend_set_user_opcode_handler((*opcode), operators_opcode_handler);
		opcode++;
		opconst++;
	}

	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(operators)
{
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(operators)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "operators support", "enabled");
	php_info_print_table_end();
}
/* }}} */

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
