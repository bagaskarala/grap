<!-- load vue component -->
<log-match
   :division-id="<?=$this->session->userdata('division_id') ?? 0;?>"
   :year="<?=$this->session->userdata('setting_year');?>"
   :city-id="<?=$this->session->userdata('setting_city_id');?>"
   city="<?=$this->session->userdata('setting_city');?>"
></log-match>