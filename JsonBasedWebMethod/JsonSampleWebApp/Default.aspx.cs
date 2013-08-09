using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace JsonSampleWebApp
{
    public partial class Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

        }

        [System.Web.Services.WebMethod]
        public static Contact getContact(Contact cnt)
        {
            cnt.name = "Abijeet Patro";
            cnt.phone = "Blah Blah";
            return cnt;
        }
    }
}