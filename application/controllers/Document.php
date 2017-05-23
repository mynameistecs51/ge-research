<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->ctl = "Document";
		$this->load->model('mdl_document');
		$now = new DateTime(null, new DateTimeZone('Asia/Bangkok'));
		$this->dt_now = $now->format('Y-m-d H:i:s');
		$this->datenow = $now->format('d/m/').($now->format('Y')+543);
	}

	public function index()
	{
		$TEXTTITLE = "<i class=\"fa fa-fw fa-folder\"></i>ระบบจัดเก็บเอกสาร งานวิจัย";
		// $PAGE = 'Document/indexDoc';
		$PAGE = 'Document/index';
		$this->data['getAllDoc'] = $this->mdl_document->getAllDoc();
		$this->mainpage($TEXTTITLE);
		$this->load->view($PAGE,$this->data);
	}

	public function mainpage($TEXTTITLE)
	{
		$this->data['header'] = $this->template->getHeader(base_url(),$TEXTTITLE);
		$this->data['footer'] = $this->template->getFooter(base_url());
		$this->data['controller'] = $this->ctl;
		$this->data["datenow"] =$this->datenow;
		$this->data['url_add']    = base_url().$this->ctl.'/formCreate/';
		$this->data['url_update']   = base_url().$this->ctl.'/formUpdate/';
	}

	public function test()
	{
		$TEXTTITLE = "เพิ่มงานวิจัย";
		$PAGENAME  = "formCreate";
		$this->mainpage($TEXTTITLE);
		$this->load->view('/Document/'.$PAGENAME,$this->data);
	}

	public function formCreate()
	{
		$TEXTTITLE = "เพิ่มงานวิจัย";
		$PAGENAME  = "formCreate";
		$this->data["datenow"] =$this->datenow;
		$this->mainpage($TEXTTITLE);
		$this->load->view('/Document/'.$PAGENAME,$this->data);
	}

	public function createDoc()
	{

		$Outline = ($_FILES['Outline'] != "")?$this->_upload_files('Outline'):'';
		$Progress = ($_FILES['Progress'] != "")?$this->_upload_files('Progress'):'';
		$Success = ($_FILES['Success'] != "")?$this->_upload_files('Success'):'';

		$data = array(
			'doc_name' => $this->input->post('name'),
			'doc_lastname' => $this->input->post('lastname'),
			'doc_moneySupport' => $this->input->post('moneySupport'),
			'doc_amount' => $this->input->post('amount'),
			'doc_publicationWhere' => $this->input->post('publicationWhere'),
			'doc_researchName' => str_replace("\n", "<br>",$this->input->post('researchName')),
			'doc_abstract' => str_replace("\n", "<br>",$this->input->post('abstract')),
			'doc_outline' => $Outline['file_name'],
			'doc_progress' => $Progress['file_name'],
			'doc_filesuccess' => $Success['file_name'],
			'dt_create' => $this->dt_now,
			'ip_create' => $_SERVER['REMOTE_ADDR'],
			);
		$this->mdl_document->insertDoc($data);
		redirect('Document','refresh');
	}

	public function formUpdate($idDoc)
	{
		$TEXTTITLE = "อัพเดทเอกสาร";
		$PAGENAME = "formUpdate";
		$this->data['dataDoc'] = $this->mdl_document->getDocID($idDoc);
		$this->mainpage($TEXTTITLE);
		$this->load->view('/Document/'.$PAGENAME,$this->data);
	}

	public function saveUpdate()
	{
		$docID = $this->input->post('doc_id');
		// $Outline = $_FILES['Outline'];
		// if($Outline !=""){$Outline = 	$this->_upload_files('Outline');$this->deleteFile($docID,"doc_outline");	echo "1";
		// }else{	echo "2";	$Outline ="";		}
		$Outline = ($_FILES['Outline'] != "")?$this->upFile($docID,'doc_outline','Outline'):'';
		$Progress = ($_FILES['Progress'] != "")?$this->upFile($docID,'doc_progress','Progress'):'';
		$Success = ($_FILES['Success'] != "")?$this->upFile($docID,'doc_filesuccess','Success'):'';

		$data = array(
			'doc_name' => $this->input->post('name'),
			'doc_lastname' => $this->input->post('lastname'),
			'doc_moneySupport' => $this->input->post('moneySupport'),
			'doc_amount' => $this->input->post('amount'),
			'doc_publicationWhere' => $this->input->post('publicationWhere'),
			'doc_researchName' => str_replace("\n", "<br>",$this->input->post('researchName')),
			'doc_abstract' => str_replace("\n", "<br>",$this->input->post('abstract')),
			'doc_outline' => $Outline['file_name'],
			'doc_progress' => $Progress['file_name'],
			'doc_filesuccess' => $Success['file_name'],
			'dt_create' => $this->dt_now,
			'ip_create' => $_SERVER['REMOTE_ADDR'],
			);
		// echo "<pre>";
		$this->db->where('doc_id',$docID);
		$this->db->update('document',$data);
		var_dump($Outline);
	}

	public function upFile($docID,$docField,$docFile)
	{
		$delFile = $this->deleteFile($docID,$docField);
		$uploadFile = $this->_upload_files($docFile);
		return $uploadFile;
	}

	public function deleteDoc()
	{
		$del_data = $this->mdl_document->deleteDoc($this->input->post('del_id'));
		// echo json_encode($del_data);
	}

	//delete select file
	public function deleteFile($id,$field)
	{
		// $this->db->where('doc_id',$id);
		$data = $this->db->query("SELECT ".$field." FROM document WHERE doc_id = '".$id."'")->result_array();  //getdocument
		// echo "<pre>";

		$del = ($data[0][$field] == "")?'':unlink('./assets/files_document/'.$data[0][$field]);

		// foreach ($data as $key => $value) {
		// 	unlink('./assets/files_document/'.$value[$field]);	//delete file
		// }
	}


	/*
	*function upload files *
	 */
	private function _upload_files($field){
		$file_name =  date('dmy_His');
		$config['upload_path'] ='./assets/files_document/';
		$config['allowed_types'] = 'pdf|';
		$config['max_size']	= '0';
		$config['remove_spaces'] = TRUE;
		$config['file_name'] = $file_name;

		$this->load->library('upload');
		$this->upload->initialize($config);
		$files = array();

		if ($this->upload->do_upload($field)){
			$files_uploaded = $this->upload->data();
		}else{
			$files_uploaded = null;
		}
		return $files_uploaded;
	}

	public function PDF($data)
	{
		// The location of the PDF file on the server.
		$filename = "./assets/files_document/".$data;

		// Let the browser know that a PDF file is coming.
		header("Content-type: application/pdf");
		header("Content-Length: " . filesize($filename));

		// Send the file to the browser.
		readfile($filename);
		exit;

	}

}/* End Controller */

/* End of file Document.php */
/* Location: ./application/controllers/Ctl_document.php */