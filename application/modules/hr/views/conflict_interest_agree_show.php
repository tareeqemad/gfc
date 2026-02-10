<style>
    .button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 16px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 20px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
    }
    .button1 {
        background-color: white;
        color: black;
        border: 2px solid #4CAF50;
    }
    .button1:hover {
        background-color: #4CAF50;
        color: white;
    }
</style>

<div style="padding: 10px; height: 490px">
    <h2 style="text-align: center">سنـــد إقـــرار والتزام</h2>
    <p style="font-size: x-large">
        حيث أنني الموظف الموقع أدناه / <?=$emp_name?> برقم وظيفي ( <?=$emp_no?> )
        بأنني اطلعت على نظام تضارب المصالح الصادر عن الشركة في العام 2023
        وتفهمت البنود الواردة به لاسيما واجبات الموظف المنصوص عليها في المادة (7) من هذا النظام ، وأقر بالتزامي بالعمل به.
    </p>
    <a onclick='javascript:parent.click_url();' style="font-size: x-large; color: #0a53be" target="_blank" href="https://ibit.ly/UPBU"> اضغط هنا لعرض النظام </a>
<hr>
    <button type="button" id="btn_ok" onclick='javascript:parent.agree_ok();' class="button button1"> أقر بذلك </button>
</div>
