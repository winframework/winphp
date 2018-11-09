<?php

namespace Win\Database\Sql\Query;

use Win\Database\Sql\Query;

/**
 * DELETE FROM
 */
class Delete extends Query {

	public function __toString() {
		return 'DELETE FROM '
				. $this->table
				. $this->where
				. $this->limit;
	}

}
