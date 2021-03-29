// お問い合わせ本文が空欄なら送信不可
function check(){
  let a=document.contact_form.contact.value;
  if(a==""){
    return false;
  }else if(!a.match(/\S/g)){
    return false;
  }
}