<?php
namespace zohoCrm;

class Api
{
	public $authToken = ''; // your authToken 

	public function sendRequest($url, $data = null)
	{
		$headers = array(
		    "Authorization: {$this->authToken}",
		);
		$ch = curl_init($url);
		
		if (isset($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      	} else {
      		curl_setopt($ch, CURLOPT_HTTPGET, 1);
      	}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	public function addLead($request)
	{
		$data = array(
			"data" => array(
				array(
				    "Company" => $request["Company"],
		            "Email" => $request["Email"],
		            "First_Name" => $request["First_Name"],
		            "Last_Name" => $request["Last_Name"],
		            "Phone" => $request["Phone"]
		        )  
	  		)
		);

		$data = json_encode($data);
		$url = 'https://www.zohoapis.com/crm/v2/Leads';		
		$result = self::sendRequest($url, $data);
		return $result;
	}

	public function addDeal($leadId)
	{
		$date = date("Y-m-d");
        $data = array(
			"data" => array(
				array(
			    	"notify_lead_owner" => false,
					"notify_new_entity_owner" => false,
					"Deals" => array(
						"Deal_Name" => "Потенциальный клиент",
					    "Closing_Date" => $date,
					    "Stage" => "Оценка пригодности"
					)
		        )  
	  		)
		);

		$data = json_encode($data);
		$url = "https://www.zohoapis.com/crm/v2/Leads/{$leadId}/actions/convert"; 	
		$result = self::sendRequest($url, $data);
		return $result;
	}

	public function getLeadByPhone($phone)
	{
		$url = "https://www.zohoapis.com/crm/v2/Leads/search?criteria=(Phone:equals:{$phone})";
		$result = self::sendRequest($url);
		return $result;
	}
}
