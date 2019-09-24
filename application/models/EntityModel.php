<?php 
class EntityModel extends CI_Model {
		public $primaryKeyField;
        public $field1;
        public $field2;
        public $isDeleted;
        public $createdBy;
        public $createdDate;
        public $updatedBy;
        public $updatedDate;

        public function __construct(){
                parent::__construct();
        }
        public function record_count() {
           return $this->db->count_all("entity_name");
        }

       public function getPaginated($limit, $start){
        	$this->load->database();
        	$query = $this->db->order_by('primaryKeyField', 'DESC');
        	$query = $this->db->get('entity_name',$limit, $start);
        	$this->db->close();
        	return $query->result();
        }
        public function get($limit){
        	$this->load->database();
        	$query = $this->db->order_by('primaryKeyField', 'DESC');
        	$query = $this->db->get('entity_name',$limit);
        	$this->db->close();
        	return $query->result();
        }
        public function getAll(){
			$this->load->database();
			$query = $this->db->get('entity_name');
			$this->db->close();
			return $query->result();
        }
        public function findOne($primaryKeyField){
        	$this->load->database();
        	$this->db->where('primaryKeyField', $primaryKeyField);
			$query = $this->db->get('entity_name');
        	$this->db->close();
        	return $query->result();
        }

        public function create($entity) {
			$this->load->database();
			$this->primaryKeyField    = $entity['primaryKeyField']; 
			$this->field1    = $entity['field1']; 
			$this->field2    = $entity['field2']; 
			$this->isDeleted    = 0; 
			
			$this->createdBy  = $entity['createdBy'];
			$this->updatedBy  = $entity['updatedBy'];
			
			$this->createdDate     = time();
			$this->updatedDate  = time();

			$primaryKeyField = $this->db->insert('entity_name', $this);
			$this->db->close();
			return $primaryKeyField;
        }
        
        public function update($entity){
			$this->load->database();
			$this->primaryKeyField    = $entity['primaryKeyField'];
			$this->field1    = $entity['field1']; 
			$this->updatedDate  = time();
			$this->createdBy  = $entity['createdBy'];
			$this->db->update('entity_name', $this, array('primaryKeyField' => $entity['primaryKeyField']));
			$this->db->close();
        }
		
        public function delete($primaryKeyField) {
        	$this->load->database();
			$sql = "DELETE FROM entity_name WHERE primaryKeyField=".$primaryKeyField;
			$this->db->simple_query($sql);
			$this->db->close();
        }


}