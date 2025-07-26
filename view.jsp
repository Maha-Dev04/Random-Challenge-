<%@ page contentType="application/json; charset=UTF-8" %>
<%@ page import="java.io.*, javax.xml.parsers.*, org.w3c.dom.*, org.json.*" %>

<%
    JSONArray challenges = new JSONArray();

    try {
        String filePath = application.getRealPath("/") + "challenges.xml";
        File xmlFile = new File(filePath);

        if (!xmlFile.exists()) {
            out.print("[]");
            return;
        }

        DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
        DocumentBuilder builder = factory.newDocumentBuilder();
        Document doc = builder.parse(xmlFile);
        NodeList nodeList = doc.getElementsByTagName("challenge");

        for (int i = 0; i < nodeList.getLength(); i++) {
            Element ch = (Element) nodeList.item(i);
            JSONObject obj = new JSONObject();

            obj.put("id", ch.hasAttribute("id") ? ch.getAttribute("id") : String.valueOf(i + 1));
            obj.put("title", getText(ch, "title"));
            obj.put("category", getText(ch, "category"));
            obj.put("difficulty", getText(ch, "difficulty"));
            obj.put("estimatedTime", getText(ch, "estimatedTime"));
            obj.put("description", getText(ch, "description"));
            obj.put("requirements", getText(ch, "requirements"));
            obj.put("tags", getText(ch, "tags"));
            obj.put("savedAt", getText(ch, "savedAt"));

            challenges.put(obj);
        }

        out.print(challenges.toString());

    } catch (Exception e) {
        // Log the error and return empty list
        e.printStackTrace();
        out.print("[]");
    }

    // Helper function for null-safe tag access
    String getText(Element element, String tag) {
        NodeList list = element.getElementsByTagName(tag);
        return (list.getLength() > 0 && list.item(0).getTextContent() != null)
                ? list.item(0).getTextContent()
                : "";
    }
%>
