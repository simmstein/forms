<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2019 Inigo Jiron <ijiron@terpmail.umd.edu>
 *
 * @author Inigo Jiron <ijiron@terpmail.umd.edu>
 * @author Natalie Gilbert <ngilb634@umd.edu>
 *
 * @license GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Forms\Db;

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

class AnswerMapper extends QBMapper {

	/**
	 * TextMapper constructor.
	 * @param IDBConnection $db
	 */
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'forms_answers', Answer::class);
	}

	// TODO: Change below functions to search by form and question id

	/**
	 * @param int $formId
	 * @param int $questionId
	 * @throws \OCP\AppFramework\Db\DoesNotExistException if not found
	 * @return Answer[]
	 */

	public function findByForm(int $formId, int $questionId): array {
		$qb = $this->db->getQueryBuilder();

        $qb->select('*')
           ->from($this->getTableName())
           ->where(
               $qb->expr()->eq('form_id', $qb->createNamedParameter($formId, IQueryBuilder::PARAM_INT))
		   )
		   ->andWhere(
			   $qb->expr()->eq('question_id', $qb->createNamedParameter($questionId, IQueryBuilder::PARAM_INT))
		   );

        return $this->findEntities($qb);
	}

	/**
	 * @param int $formId
	 * @param int $questionId
	 */
	public function deleteByFormAndQuestion(int $formId, int $questionId): void {
		$qb = $this->db->getQueryBuilder();

        $qb->delete($this->getTableName())
           ->where(
               $qb->expr()->eq('form_id', $qb->createNamedParameter($formId, IQueryBuilder::PARAM_INT))
		   )
		   ->andWhere(
			$qb->expr()->eq('question_id', $qb->createNamedParameter($questionId, IQueryBuilder::PARAM_INT))
		);

	   $qb->execute();
	}

	/**
	 * @param int $formId
	 */
	public function deleteByForm(int $formId): void {
		$qb = $this->db->getQueryBuilder();

        $qb->delete($this->getTableName())
           ->where(
               $qb->expr()->eq('form_id', $qb->createNamedParameter($formId, IQueryBuilder::PARAM_INT))
		   );

	   $qb->execute();
	}

	public function findById(int $answerId): Answer {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($answerId))
			);

		return $this->findEntity($qb);
	}

	public function deleteByQuestion(int $questionId): void {
		$qb = $this->db->getQueryBuilder();

		$qb->delete($this->getTableName())
			->where(
				$qb->expr()->eq('question_id', $qb->createNamedParameter($questionId))
			);

		$qb->execute();
	}
}
