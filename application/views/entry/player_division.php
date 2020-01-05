<!-- load vue component -->
<player-division
   division-id="<?=$this->session->userdata('division_id') ?? null;?>"
   :year="<?=$this->session->userdata('setting_year');?>"
   :city_id="<?=$this->session->userdata('setting_city_id');?>"
   city="<?=$this->session->userdata('setting_city');?>"
></player-division>