<?php

namespace MicroCMS\DAO;

use MicroCMS\Domain\Group;

class GroupDAO extends DAO 
{
    /**
     * @var \MicroCMS\DAO\GroupDAO
     */
    private $groupDAO;


    /**
     * Returns a group matching the supplied id.
     *
     * @param integer $id The group id
     *
     * @return \MicroCMS\Domain\Group|throws an exception if no matching group is found
     */
    public function find($id) {
        $sql = "select * from t_group where group_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No group matching id " . $id);
    }
     
        /**
     * Returns a list of all groups, sorted by date (most recent first).
     *
     * @return array A list of all groups.
     */
    public function findAll() {
        $sql = "select * from t_group order by group_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['group_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }
    
            /**
     * Saves a group into the database.
     *
     * @param \MicroCMS\Domain\Group $group The group to save
     */
    public function save(Group $group) {
        $groupData = array(
            'group_id' => $group->getGroup()->getId(),
            'group_name' => $group->getName(),
            'group_role' => $group->getRole()
            );

        if ($group->getId()) {
            // The group has already been saved : update it
            $this->getDb()->update('t_group', $groupData, array('group_id' => $group->getId()));
        } else {
            // The group has never been saved : insert it
            $this->getDb()->insert('t_group', $groupData);
            // Get the id of the newly created group and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $group->setId($id);
        }
    }
    
    /**
     * Removes a group from the database.
     *
     * @param @param integer $id The group id
     */
    public function delete($id) {
        // Delete the group
        $this->getDb()->delete('t_group', array('group_id' => $id));
    }
    
    /**
     * Creates an Group object based on a DB row.
     *
     * @param array $row The DB row containing Group data.
     * @return \MicroCMS\Domain\Group
     */
    protected function buildDomainObject($row) {
        $group = new Group();
        $group->setId($row['group_id']);
        $group->setName($row['group_name']);

        if (array_key_exists('group_id', $row)) {
            // Find and set the associated group
            $groupId = $row['group_id'];
            $group = $this->groupDAO->find($groupId);
            $group->setGroup($group);
        }
        
        return $group;
    }
    
}